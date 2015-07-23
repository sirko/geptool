<?php
namespace Geptool\Bundle\JiraApiBundle\Command;

//use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Geptool\Bundle\MainBundle\Entity\JiraProject;
use Geptool\Bundle\MainBundle\Entity\JiraUser;
use Geptool\Bundle\MainBundle\Entity\JiraWorklog;

/**
 * JiraImportCommand
 * Symfony cli command class, imports data from jira
 *
 * @author Roman Liashenko <lyashenkor@gmail.com>
 *
 */
class JiraImportCommand extends ContainerAwareCommand
{
    /**
     * Input interface
     * @var InputInterface
     */
    protected $input;

    /**
     * Output interface
     * @var OutputInterface
     */
    protected $output;

    /**
     * Required method, to define command info
     * @return nothing
     */
    protected function configure()
    {
        $this
            ->setName('geptool:jira-import')
            ->setDescription('Import data from Jira')
            ->addArgument(
                'entity',
                InputArgument::REQUIRED,
                'What should be imported projects|users|worklogs'
            )
            ->addOption(
                'from-date',
                null,
                InputOption::VALUE_REQUIRED,
                'Start import work-logs from date',
                date("Y-m-d")
            );
    }

    /**
     * Entry point of the command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        switch ($input->getArgument('entity')) {
            case 'projects':
                $this->importProjects();
                break;
            case 'users':
                $this->importUsers();
                break;
            case 'worklogs':
                $this->importWorklogs($input->getOption('from-date'));
                break;

            default:
                $output->writeln('<error>Unknown entity type to import. Should be projects|users|worklogs</error>');
                $output->writeln('<error>Please try again</error>');
                break;
        }
    }

    protected function importProjects()
    {
        $projectService = $this->getContainer()->get('jira_api.project');

        $projectRepository = $this->getContainer()
            ->get('doctrine')
            ->getRepository('GeptoolMainBundle:JiraProject');

        foreach ($projectService->getAll() as $project) {
            if ($projectRepository->findOneByProjectKey($project['key'])) {
                $this->output->writeln('<info>' . $project['name'] . ' - already exists</info>');
                continue;
            }

            $this->output->writeln('<info>' . $project['name'] . ' - imported</info>');
            $jiraProject = new JiraProject();
            $jiraProject->setId($project['id']);
            $jiraProject->setProjectKey($project['key']);
            $jiraProject->setName($project['name']);

            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->persist($jiraProject);
            $em->flush();
            $em->clear();
        }
    }

    protected function importUsers()
    {
        $userRepository = $this->getContainer()->get('doctrine')
            ->getRepository('GeptoolMainBundle:JiraUser');
        $userService = $this->getContainer()->get('jira_api.user_search');
        foreach ($userService->search(array('project' => 'MSF')) as $user) {
            if ($jiraUser = $userRepository->findOneByUserName($user['name'])) {
                 $this->output->writeln('<info>' . $user['displayName'] . ' - already exists</info>');
                continue;
            }
            $this->output->writeln('<info>' . $user['displayName'] . ' - imported</info>');
            $jiraUser = new JiraUser();
            $jiraUser->setUserName($user['name']);
            $jiraUser->setUserKey($user['key']);
            $jiraUser->setEmail($user['emailAddress']);
            $jiraUser->setName($user['displayName']);
            $jiraUser->setIsActive($user['active']);
            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->persist($jiraUser);
            $em->flush();
            $em->clear();
        }

    }

/**
 * importWorklogs function
 * @param string $fromDate date in "Y-m-d" format
 *
 * @todo Add fromDate validation
 */
    protected function importWorklogs($fromDate)
    {
        $importedCount = 0;
        $count = 0;

        $searchService = $this->getContainer()->get('jira_api.search');
        $searchService->setStart(0)->setLimit(1000)->setFields('key');


        $issueService = $this->getContainer()->get('jira_api.issue');
        $issues = $searchService->search(
            array(
                'jql' => "updated >= $fromDate",
            )
        );
        $startFrom = 0;
        $total = $issues['total'];

        while ($total > 0 ) {
            $searchService->setStart($startFrom)->setLimit(1000)->setFields('key');
            $issues = $searchService->search(
                array(
                    'jql' => "updated >= $fromDate",
                )
            );
            $result = $this->getWorklogsByIssues($issues, $fromDate);

            $startFrom += 1000;
            $total -= 1000;
            $this->output->writeln('<info>Start:' . $startFrom . '</info>');
            $importedCount += $result['total'];
            $count += $result['count'];
        }
        $this->output->writeln('<info>Total worklogs: ' . $count . '</info>');
        $this->output->writeln('<info>Amount: ' . $importedCount . '</info>');
    }

    private function getWorklogsByIssues($issues, $fromDate)
    {
        $total = 0;
        $count = 0;
        $notFoundProjects = array();
        $notFoundUsers = array();
        $projectRepository = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository('GeptoolMainBundle:JiraProject');
        $userRepository = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository('GeptoolMainBundle:JiraUser');
        $worklogRepository = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository('GeptoolMainBundle:JiraWorklog');
        $issueService = $this->getContainer()->get('jira_api.issue');

        $em = $this->getContainer()->get('doctrine')->getManager();

        if (!empty($issues['issues'])) {
            foreach ($issues['issues'] as $issue) {

                preg_match('/(.+)-/i', $issue['key'], $projectKey);
                $projectKey = $projectKey[1];
                $jiraProject = $projectRepository->findOneByProjectKey($projectKey);
                if (!isset($jiraProject)) {
                    $notFoundProjects[] = $projectKey;
                    continue;
                }

                $worklogs = $issueService->getWorklog($issue['key']);

                //$issuesStr .= "<li>" . $issue['key'] . "</li>";

                if ($worklogs) {
                    foreach ($worklogs['worklogs'] as $log) {
                        if ($worklogRepository->findOneById($log['id'])) {
                            continue;
                        }
                        $logDate = date("Y-m-d", strtotime($log['started']));
                        if ($logDate >= $fromDate) {
                            $jiraUser = $userRepository->findOneByUserName($log['author']['name']);
                            if (!isset($jiraUser)) {
                                $notFoundUsers[] = $log['author']['name'];
                                continue;
                            }
                            //dump($log);

                            $count++;
                            $total += $log['timeSpentSeconds'];
                            $jiraWorklog = new JiraWorklog();
                            $jiraWorklog->setId($log['id']);
                            $jiraWorklog->setTimeSpent($log['timeSpentSeconds']);
                            $jiraWorklog->setStarted(new \DateTime($log['started']));

                            $jiraWorklog->setJiraProject($jiraProject);
                            $jiraWorklog->setJiraUser($jiraUser);

                            $em->persist($jiraWorklog);

                        }
                    }
                    $em->flush();
                    $em->clear();
                }
            }
        }

        return
        array(
            'total' => $total,
            'count' => $count,
            'notFoundProjects' => $notFoundProjects,
            'notFoundUsers' => $notFoundUsers
        );
    }
}
