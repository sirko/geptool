<?php

namespace Geptool\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Geptool\Bundle\MainBundle\Entity\JiraProject;
use Geptool\Bundle\MainBundle\Entity\JiraUser;
use Geptool\Bundle\MainBundle\Entity\JiraWorklog;

use FOS\RestBundle\Controller\FOSRestController;

class DefaultController extends FOSRestController
{
    public function getUsersAction($name)
    {
        $em = $this->getDoctrine()->getManager();

        $jiraUsers = $em->getRepository("GeptoolMainBundle:JiraUser")->findAll();
        foreach ($jiraUsers as $jiraUser) {
            $jiraUser->setTotalTime();
        }

        return $jiraUsers;
    }
}
