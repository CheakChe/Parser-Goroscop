<?php


class Model
{
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new PDO(DNS, DBUSER, DBPASS);
        } catch (PDOException $e) {
            die(Log::writeLog('Подключение не удалось: ' . $e->getMessage()));
        }
    }

    protected function fetch_all($query): array
    {
        try {
            $fetch_assoc = $this->db->query($query);
        } catch (PDOException $exception) {
            die(Log::writeLog('Ошибка: ' . $exception->getMessage()));
        }
        return $fetch_assoc->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function fetch_assoc($query)
    {
        try {
            $fetch_assoc = $this->db->query($query);
        } catch (PDOException $exception) {
            die(Log::writeLog('Ошибка: ' . $exception->getMessage()));
        }
        return $fetch_assoc->fetch(PDO::FETCH_ASSOC);
    }

    protected function query($query): void
    {
        try {
            $this->db->exec($query);
        } catch (PDOException $exception) {
            die(Log::writeLog('Ошибка: ' . $exception->getMessage()));
        }
    }

}