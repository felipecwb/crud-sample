<?php

namespace CrudSample\Storage;

use CrudSample\Domain\Profile as ProfileEntity;

class Profile extends Database
{
    public function findById($id)
    {
        $data = $this->db->createQueryBuilder()
            ->select('profile_id', 'profile_name', 'profile_type')
            ->from('tb_profile', 'p')
            ->where('p.profile_id = :id')
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
        $rows = $this->db->createQueryBuilder()
            ->select('profile_id', 'profile_name', 'profile_type')
            ->from('tb_profile', 'p')
            ->execute()
            ->fetchAll();

        if (array_key_exists('name', $fields)) {
            $dqb->andWhere('profile_name LIKE :name')
                ->setParameter(':name', $fields['name'] . '%');
        }

        if (array_key_exists('type', $fields)) {
            $dqb->andWhere('profile_type LIKE :type')
                ->setParameter(':type', $fields['type'] . '%');
        }

        foreach ($rows as &$row) {
            $row = $this->exchange($row);
        }

        return $rows;
    }

    protected function exchange(array $data = array())
    {
        return (new ProfileEntity())
            ->setId($data['profile_id'])
            ->setName($data['profile_name'])
            ->setType($data['profile_type']);
    }
}
