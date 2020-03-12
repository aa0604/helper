<?php

namespace xing\helper\yii\migrations;


class Migrations
{

    /**
     * @param \xing\helper\yii\MyActiveRecord $model
     */
    public static function create($model, $tableCommnt = '')
    {
        $schema = $model->getTableSchema();
        echo '
        $tableOptions = null;
    
        if ($this->db->driverName === \'mysql\') {
            $tableOptions = \'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "' . $tableCommnt . '"\';
        }';
        
        echo "\n        \$this->createTable('{$schema->name}', [\r\n";
        foreach ($schema->columns as $key => $m) {
            echo "                  '{$m->name}'" . '     => $this->';
            
            if ($m->isPrimaryKey) {
                echo 'primaryKey()->';
            } else {
                $size = $m->size ? $m->size : '';
                switch ($m->type) {
                    case 'integer':
                    case 'smallint':
                    case 'tinyint':
                        echo "integer({$size})->";
                        break;
                    default:
                        echo $m->type . "({$size})->";
                        break;
                }
            }
            
            if (!$m->allowNull) echo 'notNull()->';
            echo "comment('{$m->comment}'),\r\n";
            
        }
        echo '      ], $tableOptions);';
    }
}