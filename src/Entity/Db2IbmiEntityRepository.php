<?php

namespace Nealis\Db2IbmiEntityRepository\Entity;

use DoctrineDbalIbmi\Platform\DB2IBMiPlatform;
use Nealis\EntityRepository\Entity\EntityRepository;

abstract class Db2IbmiEntityRepository extends EntityRepository
{

    public function initDb($library = '')
    {
        if($this->checkDb($library)) return false;
        if (!empty($library)) {
            $library = $library . '/';
        } else {
            $library = '';
        }

        $stmt = str_ireplace('{{ LIBRARY }}', $library, $this->createStmt);
        return $this->connection->exec($stmt);
    }

    public function checkDb($library = '')
    {
        $tableName = $this->getTableName();
        /** @var DB2IBMiPlatform $platform */
        $platform = $this->getDatabasePlatform();
        $sql = $platform->getListTablesSQL($library);
        $tables = $this->getConnection()->fetchAll($sql);
        $tables = array_map(function($el) { return $el['NAME']; }, $tables);
        return in_array($tableName, array_values($tables));
    }

}
