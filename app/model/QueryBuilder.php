<?php

namespace App\Model;
use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder{
	protected $pdo;
	protected $queryFactory;
	
	public function __construct(PDO $pdo, QueryFactory $factory)
	{
		$this->pdo = $pdo;
		$this->queryFactory = $factory;
	}
	
	public function getAll($table){
		$select = $this->queryFactory->newSelect();
		$select->cols( ["*"] );
		$select->from($table);
		
		$sth = $this->pdo->prepare($select->getStatement());
		$sth->execute($select->getBindValues());
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getOne($table,$id){
		$select = $this->queryFactory->newSelect();
		$select->cols(["*"]);
		$select->from($table);
		$select->where('id = :id');
		$select->bindValue('id', $id);

		$sth = $this->pdo->prepare($select->getStatement());
		$sth->execute($select->getBindValues());
		return $sth->fetch(PDO::FETCH_ASSOC);
	}
	public function insert($table,$data){
		$insert = $this->queryFactory->newInsert();
		$insert->into($table)
		->cols($data);
		$sth = $this->pdo->prepare($insert->getStatement());
		$sth->execute($insert->getBindValues());
	}
	public function delete($table,$id){
		$delete = $this->queryFactory->newDelete();
		$delete
			->from($table)
			->where('id = :id')
			->bindValue('id', $id);
			$sth = $this->pdo->prepare($delete->getStatement());
			$sth->execute($delete->getBindValues());
	}
	public function update($table,$data,$id){
		$update = $this->queryFactory->newUpdate();
		$update
			->table($table)                  // update this table
			->cols($data)              // bind values as "SET bar = :bar"
			->where('id = :id')           // AND WHERE these conditions
			->bindValue('id', $id);   // bind one value to a placeholder
		$sth = $this->pdo->prepare($update->getStatement());
		$sth->execute($update->getBindValues());
	}
}
