<?php
namespace  Geptool\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Geptool\Bundle\MainBundle\Entity\JiraUser;

/**
 * Jira worklog entity
 *
 * @ORM\Entity
 * @ORM\Table(name="user_dayoff")
 *
 * @JMS\ExclusionPolicy("all")
 */
class UserDayoff
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
     * @ORM\ManyToOne(targetEntity="JiraUser", inversedBy="daysoff", cascade={"all","merge","persist","refresh","remove"})
     * @ORM\JoinColumn(name="jira_user_id", referencedColumnName="id")
     *
     **/
    protected $jiraUser;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $month;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $days;

    /**
     * Set id
     *
     * @param integer $id
     * @return UserDayoff
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
     * Set month
     *
     * @param integer $month
     * @return UserDayoff
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set days
     *
     * @param integer $days
     * @return UserDayoff
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return integer
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set jiraUser
     *
     * @param \Geptool\Bundle\MainBundle\Entity\JiraUser $jiraUser
     * @return UserDayoff
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
}
