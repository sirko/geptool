<?php

namespace Geptool\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Geptool\Bundle\MainBundle\Entity\JiraProject;
use Geptool\Bundle\MainBundle\Entity\JiraUser;
use Geptool\Bundle\MainBundle\Entity\JiraWorklog;

/**
 * Default controller class
 */
class DefaultController extends Controller
{
  /**
   * Default action
   * @return Symfony\Component\HttpFoundation\Response response
   */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $jiraUsers = $em->getRepository("GeptoolMainBundle:JiraUser")->findAll();
        $jiraProjects = $em->getRepository("GeptoolMainBundle:JiraProject")->findAll();

        return $this->render('GeptoolMainBundle:Default:index.html.twig',
            array('jira_users' => $jiraUsers, 'jira_projects' => $jiraProjects ));
    }
}
