<?php
namespace Overtrue\Wechat;

use Overtrue\Wechat\Utils\Bag;

/**
 * 用户组
 */
class Group
{
    const API_GET                 = 'https://api.weixin.qq.com/cgi-bin/groups/get';
    const API_CREATE              = 'https://api.weixin.qq.com/cgi-bin/groups/create';
    const API_UPDATE              = 'https://api.weixin.qq.com/cgi-bin/groups/update';
    const API_USER_GROUP_ID       = 'https://api.weixin.qq.com/cgi-bin/groups/getid';
    const API_MEMBER_UPDATE       = 'https://api.weixin.qq.com/cgi-bin/groups/members/update';
    const API_MEMBER_BATCH_UPDATE = 'https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate';

    /**
     * Http对象
     *
     * @var Http
     */
    protected $http;


    /**
     * constructor
     *
     * @param string $appId
     * @param string $appSecret
     */
    public function __construct($appId, $appSecret)
    {
        $this->http = new Http(new AccessToken($appId, $appSecret));
    }

    /**
     * 创建分组
     *
     * @param string $name
     *
     * @return integer
     */
    public function create($name)
    {
        $params = array(
                    'group' => array(
                                'name' => $name,
                               ),
                  );

        $response = $this->http->jsonPost(self::API_CREATE, $params);

        return $response['group'];
    }

    /**
     * 获取所有分组
     *
     * @return array
     */
    public function lists()
    {
        $response = $this->http->get(self::API_GET);

        return $response['groups'];
    }

    /**
     * 更新组名称
     *
     * @param int     $groupId
     * @param string  $name
     *
     * @return Overtrue\Wechat\Group
     */
    public function update($groupId, $name)
    {
        $params = array(
                    'group' => array(
                                'id'   => $groupId,
                                'name' => $name,
                               ),
                  );

        $this->http->jsonPost(self::API_UPDATE, $params);

        return true;
    }

    /**
     * 获取用户所在分组
     *
     * @param string $openId
     *
     * @return integer
     */
    public function userGroup($openId)
    {
         $params = array(
                    'openid' => $openId,
                  );

        $response = $this->http->jsonPost(self::API_USER_GROUP_ID, $params);

        return $response['groupid'];
    }

    /**
     * 移动单个用户
     *
     * @param string  $openId
     * @param int     $groupId
     *
     * @return boolean
     */
    public function moveUser($openId, $groupId)
    {
        $params = array(
                   'openid'     => $openId,
                   'to_groupid' => $groupId,
                  );

        $this->http->jsonPost(self::API_MEMBER_UPDATE, $params);

        return true;
    }

    /**
     * 批量移动用户
     *
     * @param array   $openIds
     * @param int     $groupId
     *
     * @return boolean
     */
    public function moveUsers(array $openIds, $groupId)
    {
        $params = array(
                   'openid_list' => $openIds,
                   'to_groupid'  => $groupId,
                  );

        $this->http->jsonPost(self::API_MEMBER_BATCH_UPDATE, $params);

        return true;
    }
}