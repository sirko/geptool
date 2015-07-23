<?php
namespace  Geptool\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Geptool\Bundle\MainBundle\Entity\JiraWorklog;


/**
 * Jira user entity
 *
 * @ORM\Entity
 * @ORM\Table(name="jira_user")
 */
class JiraUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    protected $userName;

    /**
     *
     * @ORM\Column(type="string", length=255)
     *
     */
    protected $userKey;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\OneToMany(targetEntity="JiraWorklog", mappedBy="jiraUser")
     **/
    protected $jiraWorklogs;

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
     * Set userName
     *
     * @param string $userName
     * @return JiraUser
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userKey
     *
     * @param string $userKey
     * @return JiraUser
     */
    public function setUserKey($userKey)
    {
        $this->userKey = $userKey;

        return $this;
    }

    /**
     * Get userKey
     *
     * @return string
     */
    public function getUserKey()
    {
        return $this->userKey;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return JiraUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return JiraUser
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return JiraUser
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
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
     * @return JiraUser
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

    /**
     * Get total spent time by user
     *
     * @return int [time spent by user]
     */
    public function getTotalSpentTime()
    {
        $timeSpent = 0;
        foreach ($this->jiraWorklogs as $log) {
            $timeSpent += $log->getTimeSpent();
        }

        return gmdate("H:i:s", $timeSpent);
    }
}
