<?php 
namespace Star\Repositories\Contracts;

interface InterfaceUser
{
        // public function getById($id);
        // public function getByMobile($mobile);
        public function getWxmpsById($uid);
        public function hasMobile($mobile);
        public function save($data);
        public function update($data);
        // public function all($columns = ['*'], $orderBy = 'id', $sortBy = 'asc');
}
