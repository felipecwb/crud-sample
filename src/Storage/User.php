<?php

namespace CrudSample\Storage;

use DateTime;
use CrudSample\Domain\User as UserEntity;
use CrudSample\Domain\Profile as ProfileEntity;

class User extends Database
{
    public function findById($id)
    {
        $data = $this->db->createQueryBuilder()
            ->select(
                'user_id', 'user_fullname', 'user_email', 'user_password', 'user_birth_date', 'user_profile',
                'profile_id', 'profile_name', 'profile_type')
            ->from('tb_user', 'u')
            ->innerJoin('u', 'tb_profile', 'p', 'u.user_profile = p.profile_id')
            ->where('u.user_id = :id')
            ->setMaxResults(1)
            ->setParameter(':id', $id)
            ->execute()
            ->fetch();

        if (! $data) {
            return null;
        }

        return $this->exchange($data);
    }

    public function find(array $fields = array())
    {
        $dqb = $this->db->createQueryBuilder()
            ->select(
                'user_id', 'user_fullname', 'user_email', 'user_password', 'user_birth_date', 'user_profile',
                'profile_id', 'profile_name', 'profile_type')
            ->from('tb_user', 'u')
            ->innerJoin('u', 'tb_profile', 'p', 'u.user_profile = p.profile_id');

        // where as
        if (array_key_exists('email', $fields)) {
            $dqb->andWhere('user_email = :email')
                ->setParameter(':email', $fields['email']);
        }

        if (array_key_exists('name', $fields)) {
            $dqb->andWhere('user_fullname LIKE :name')
                ->setParameter(':name', $fields['name'] . '%');
        }

        if (array_key_exists('profile', $fields)) {
            $dqb->andWhere('profile_id = :profile')
                ->setParameter(':profile', $fields['profile']);
        }

        $rows = $dqb->execute()->fetchAll();

        foreach ($rows as &$row) {
            $row = $this->exchange($row);
        }

        return $rows;
    }

    public function create(UserEntity $user)
    {

    }

    public function update(UserEntity $user)
    {

    }

    public function remove(UserEntity $user)
    {

    }

    protected function exchange(array $data = array())
    {
        $profile = new ProfileEntity();
        if (array_key_exists('profile_id', $data)) {
            $profile->setId($data['profile_id'])
                ->setName($data['profile_name'])
                ->setType($data['profile_type']);
        } else {
            $profileStorage = new Profile($this->db);
            $profile = $profileStorage->findById('user_profile');
        }

        return (new UserEntity)
            ->setId($data['user_id'])
            ->setName($data['user_fullname'])
            ->setEmail($data['user_email'])
            ->setPassword($data['user_password'])
            ->setBirthDate(new DateTime($data['user_birth_date']))
            ->setProfile($profile);
    }
}
