<?php 
namespace Star\Repositories\Contracts;

interface InterfaceUser
{
        // public function getById($id);
        // public function getByMobile($mobile);
        public function findAllMps();
        public function findMpById($id);
        public function createMp($wxData);
        public function hasMobile($mobile);
        public function saveUser($data);
        public function updateUser($data);
        // public function all($columns = ['*'], $orderBy = 'id', $sortBy = 'asc');
}
