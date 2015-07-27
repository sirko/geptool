<?php
namespace  Geptool\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Geptool\Bundle\MainBundle\Entity\JiraWorklog;


/**
 * Jira user entity
 *
 * @ORM\Entity
 * @ORM\Table(name="jira_user")
 *
 * @JMS\ExclusionPolicy("all")
 */
class JiraUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $userName;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $userKey;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $capacity;

    /**
     * @ORM\Column(type="boolean")
     * @JMS\Expose
     * @JMS\Type("boolean")
     */
    protected $isActive;

    /**
     * @ORM\OneToMany(targetEntity="JiraWorklog", mappedBy="jiraUser")
     *
     **/
    protected $jiraWorklogs;

    /**
     * @ORM\OneToMany(targetEntity="UserDayoff", mappedBy="jiraUser")
     *
     **/
    protected $daysoff;

    /**
     * @var integer Total logged time
     * @JMS\Expose
     *
     */
    protected $totalSpentTime;


    /**
     * @var integer Total logged time,
     * recalculated taking into account the capacity factor
     * @JMS\Expose
     *
     */
    protected $totalSpentTimeEffective;

    /**
     * @var integer Total logged time
     * @JMS\Expose
     */
    protected $totalSpentTimeFormatted;

    /**
     * @var integer Total logged time
     * @JMS\Expose
     */
    protected $totalSpentTimeEffectiveFormatted;

    /**
     * @var integer time should be logged by user
     * @JMS\Expose
     */
    protected $neededLoggedTime;

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
     * Converts in seconds into "H:i:s" format
     * @param integer $time seconds
     *
     * @return string formatted spent time
     */
    public function formatTime($time)
    {
        return sprintf("%02d%s%02d%s%02d", floor($time/3600), ':', ($time/60)%60, ':', $time%60);
    }

    /**
     * Fill accumulated properties
     * @param string $fromDate <date in "Y-m-d" format>
     *
     * @return JiraUser
     */
    public function setTotalTime($fromDate)
    {
        $this->totalSpentTime = $this->getTotalSpentTime($fromDate);
        $this->totalSpentTimeFormatted = $this->formatTime($this->totalSpentTime);

        $capacity = $this->getCapacity() ? $this->getCapacity() : 6;

        $this->totalSpentTimeEffective = $this->totalSpentTime * ($capacity/6);
        $this->totalSpentTimeEffectiveFormatted = $this->formatTime($this->totalSpentTimeEffective);

        $currentMonth = date('n');
        $daysoff = 0;
        foreach ($this->daysoff as $dayoff) {
            if ($dayoff->getMonth() == $currentMonth) {
                $daysoff += $dayoff->getDays();
            }
        }

        $this->neededLoggedTime = $capacity*(18 - $daysoff);

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
     * Set capacity
     *
     * @param integer $capacity
     * @return JiraUser
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return integer
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Add daysoff
     *
     * @param \Geptool\Bundle\MainBundle\Entity\UserDayoff $daysoff
     * @return JiraUser
     */
    public function addDaysoff(\Geptool\Bundle\MainBundle\Entity\UserDayoff $daysoff)
    {
        $this->daysoff[] = $daysoff;

        return $this;
    }

    /**
     * Remove daysoff
     *
     * @param \Geptool\Bundle\MainBundle\Entity\UserDayoff $daysoff
     */
    public function removeDaysoff(\Geptool\Bundle\MainBundle\Entity\UserDayoff $daysoff)
    {
        $this->daysoff->removeElement($daysoff);
    }

    /**
     * Get daysoff
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDaysoff()
    {
        return $this->daysoff;
    }
}
