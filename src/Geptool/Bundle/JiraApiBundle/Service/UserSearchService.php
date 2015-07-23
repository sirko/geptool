<?php

namespace Geptool\Bundle\JiraApiBundle\Service;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;

/**
 * Service class that handles searches.
 */
class UserSearchService extends AbstractPagedService
{
    /**
     * Search for issues.
     *
     * @param array $params
     *
     * @return boolean|array
     */
    public function search(array $params = array())
    {
        return $this->performQuery(
            $this->createUrl('user/assignable/search', $params)
        );
    }
}
