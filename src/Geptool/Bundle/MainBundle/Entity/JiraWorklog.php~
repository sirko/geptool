<?php
namespace  Geptool\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Geptool\Bundle\MainBundle\Entity\JiraProject;
use Geptool\Bundle\MainBundle\Entity\JiraUser;

/**
 * Jira worklog entity
 *
 * @ORM\Entity
 * @ORM\Table(name="jira_worklog")
 *
 * @JMS\ExclusionPolicy("all")
 */
class JiraWorklog
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @JMS\Expose
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="JiraUser", inversedBy="jiraWorklogs", cascade={"all","merge","persist","refresh","remove"})
     * @ORM\JoinColumn(name="jira_user_id", referencedColumnName="id")
     *
     **/
    protected $jiraUser;

    /**
     * @ORM\ManyToOne(targetEntity="JiraProject", inversedBy="jiraWorklogs", cascade={"all","merge","persist","refresh","remove"})
     * @ORM\JoinColumn(name="jira_project_id", referencedColumnName="id")
     *
     **/
    protected $jiraProject;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $timeSpent;

    /**
     * @ORM\Column(type="date")
     * @JMS\Expose
     * @JMS\Type("DateTime")
     */
    protected $started;



    /**
     * Set id
     *
     * @param integer $id
     * @return JiraWorklog
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
     * Set timeSpent
     *
     * @param integer $timeSpent
     * @return JiraWorklog
     */
    public function setTimeSpent($timeSpent)
    {
        $this->timeSpent = $timeSpent;

        return $this;
    }

    /**
     * Get timeSpent
     *
     * @return integer
     */
    public function getTimeSpent()
    {
        return $this->timeSpent;
    }

    /**
     * Set jiraUser
     *
     * @param \Geptool\Bundle\MainBundle\Entity\JiraUser $jiraUser
     * @return JiraWorklog
     */
    public function setJiraUser(\Geptool\Bundle\MainBundle\Entity\JiraUser $jiraUser = null)
    {
        $this->jiraUser = $jiraUser;

        return $this;
    }

    /**
     * Get jiraUser
     *
     * @return \Geptool\Bundle\MainBundle\Entity\JiraUser
     */
    public function getJiraUser()
    {
        return $this->jiraUser;
    }

    /**
     * Set jiraProject
     *
     * @param \Geptool\Bundle\MainBundle\Entity\JiraProject $jiraProject
     * @return JiraWorklog
     */
    public function setJiraProject(\Geptool\Bundle\MainBundle\Entity\JiraProject $jiraProject = null)
    {
        $this->jiraProject = $jiraProject;

        return $this;
    }

    /**
     * Get jiraProject
     *
     * @return \Geptool\Bundle\MainBundle\Entity\JiraProject
     */
    public function getJiraProject()
    {
        return $this->jiraProject;
    }

    /**
     * Set started
     *
     * @param integer $started
     * @return JiraWorklog
     */
    public function setStarted($started)
    {
        $this->started = $started;

        return $this;
    }

    /**
     * Get started
     *
     * @return integer
     */
    public function getStarted()
    {
        return $this->started;
    }
}
