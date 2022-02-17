<?php


class QueryBuilder
{
    protected $pdo;

    /**
     * Передаем объект класса PDO для дальнейшего его использования
     *
     * @param $pdo object
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Получаем все записи из переданной таблицы
     *
     * @param $table string
     * @return array
     */

    public function getAll($table) {
        $sql = 'SELECT * FROM '.$table;
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Получаем данные по id для отображения
     *
     * @param $table string
     * @param $id int
     * @return array
     */
    public function show($table, $id) {
        if(!empty($table) && !empty($id)) {
            $sql = "SELECT * FROM $table WHERE id=:id";
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            return $result;
        }
    }

    /**
     * Добавляем новую запись в таблицу
     *
     * @param $table string
     * @param $data array
     * @return string
     */
    public function create($table, $data) {
        if(!empty($table) && !empty($data)) {
            $keys = '';
            $tags = '';
            foreach ($data as $key=>$value) {
                $keys .= $key.',';
                $tags .= ':'.$key.',';
            }
            $keys = substr($keys,0,-1);
            $tags = substr($tags,0,-1);

            $sql = "INSERT INTO $table ($keys) VALUES ($tags)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute($data);
            return $this->pdo->lastInsertId();

        } else {
            return 'Вы не передали имя базы данных и записи';
        }
    }

    /**
     * Обновляем запись
     *
     * @param $table string
     * @param $data array
     * @param $id int
     * @return string
     */
    public function update($table, $data, $id) {
        if(!empty($table) && !empty($data)) {
            $keys = '';
            foreach ($data as $key=>$value) {
                $keys .= $key.'=:'.$key.',';
            }
            $keys = substr($keys,0,-1);
            $data['id'] = $id;

            $sql = "UPDATE $table SET $keys WHERE id=:id";
            $statement = $this->pdo->prepare($sql);
            $statement->execute($data);
        } else {
            return 'Вы не передали имя базы данных и записи';
        }
    }

    /**
     * Удаляем запись
     *
     * @param $table string
     * @param $id int
     */
    public function delete($table, $id) {
        if(!empty($table) && !empty($id)) {
            $sql = "DELETE FROM $table WHERE id=:id";
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();
        }
    }
}