<?php 
namespace Star\Repositories\Contracts;

interface InterfaceUser
{
        // public function getById($id);
        // public function getByMobile($mobile);
        public function getWxmpsById($uid);
        public function hasMobile($mobile);
        public function saveUser($data);
        public function updateUser($data);
        // public function all($columns = ['*'], $orderBy = 'id', $sortBy = 'asc');
}
