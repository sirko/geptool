<?php
namespace  Geptool\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Geptool\Bundle\MainBundle\Entity\JiraWorklog;


/**
 * General project entity
 *
 * @ORM\Entity
 * @ORM\Table(name="jira_project")
 *
 * @JMS\ExclusionPolicy("all")
 */
class JiraProject
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $projectKey;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="JiraWorklog", mappedBy="jiraProject")
     *
     **/
    protected $jiraWorklogs;

    /**
     * @var integer Total logged time
     * @JMS\Expose
     *
     */
    protected $totalSpentTime;

    /**
     * @var integer Total logged time
     * @JMS\Expose
     */
    protected $totalSpentTimeFormatted;

    /**
     * __toString implementation
     * @return string name of the project
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get total spent time on project
     * @param string $fromDate <date in "Y-m-d" format>
     *
     * @todo add date validation
     * @return int <time spent by user>
     */
    public function getTotalSpentTime($fromDate)
    {
        $timeSpent = 0;
        foreach ($this->jiraWorklogs as $log) {
            $timeSpent += $log->getTimeSpent();
        }

        return $timeSpent;
    }

    /**
     * Total spent time in "H:i:s" format
     * @param string $fromDate <date in "Y-m-d" format>
     *
     * @return string formatted spent time
     */
    public function getTotalSpentTimeFormatted($fromDate)
    {
        $timeSpent = $this->getTotalSpentTime($fromDate);

        return sprintf("%02d%s%02d%s%02d", floor($timeSpent/3600), ':', ($timeSpent/60)%60, ':', $timeSpent%60);
    }

    /**
     * Fill accumulated properties
     * @param string $fromDate <date in "Y-m-d" format>
     *
     * @return JiraProject
     */
    public function setTotalTime($fromDate)
    {
        $this->totalSpentTime = $this->getTotalSpentTime($fromDate);
        $this->totalSpentTimeFormatted = $this->getTotalSpentTimeFormatted($fromDate);

        return $this;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return JiraProject
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set projectKey
     *
     * @param string $projectKey
     * @return JiraProject
     */
    public function setProjectKey($projectKey)
    {
        $this->projectKey = $projectKey;

        return $this;
    }

    /**
     * Get projectKey
     *
     * @return string
     */
    public function getProjectKey()
    {
        return $this->projectKey;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return JiraProject
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return JiraProject
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jiraWorklogs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add jiraWorklogs
     *
     * @param \Geptool\Bundle\MainBundle\Entity\JiraWorklog $jiraWorklogs
     * @return JiraProject
     */
    public function addJiraWorklog(\Geptool\Bundle\MainBundle\Entity\JiraWorklog $jiraWorklogs)
    {
        $this->jiraWorklogs[] = $jiraWorklogs;

        return $this;
    }

    /**
     * Remove jiraWorklogs
     *
     * @param \Geptool\Bundle\MainBundle\Entity\JiraWorklog $jiraWorklogs
     */
    public function removeJiraWorklog(\Geptool\Bundle\MainBundle\Entity\JiraWorklog $jiraWorklogs)
    {
        $this->jiraWorklogs->removeElement($jiraWorklogs);
    }

    /**
     * Get jiraWorklogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJiraWorklogs()
    {
        return $this->jiraWorklogs;
    }

}
