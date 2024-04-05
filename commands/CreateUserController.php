<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\User;
use Exception;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\console\widgets\Table;
use yii\helpers\Console;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CreateUserController extends Controller
{

    public $name;
    public $username;
    public $password;
    
    public function options($actionID)
    {
        return ['name','username', 'password'];
    }
    
    public function optionAliases()
    {
        return [
            'n' => 'name',
            'u' => 'username',
            'p' => 'password',
        ];
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        try {

            if (is_null($this->username)) {
               throw new Exception("Parâmetro --username é obrigátorio");
            }

            if (is_null($this->name)) {
                throw new Exception("Parâmetro --name é obrigátorio");
            }

            if (is_null($this->password)) {
                throw new Exception("Parâmetro --password é obrigátorio");
            }

            $user = User::findOne(['username' => $this->username]);
            if (is_null($user)) {
                $user = new User;
                $user->name = $this->name;
                $user->username = $this->username;
                $user->setPassword($this->password);
                $user->save();
                $user->refresh();
                $this->stdout("\nUsuario criado com sucesso!\n", Console::FG_GREEN, Console::BOLD);
                $color = Console::FG_BLUE;
            } else {
                $this->stdout("\nusername em uso\n", Console::FG_RED, Console::BOLD);
                $color = Console::FG_YELLOW;
            }
            $table = Table::widget([
                'headers' => ['Name', 'Username', 'Created'],
                'rows' => [
                    [$user->name, $user->username, $user->created_at]
                ],
            ]);
            $this->stdout($table, $color);
            return ExitCode::OK;
        } catch (Exception $e) {
        
           $this->stdout("\n".$e->getMessage()."\n\n", Console::FG_RED, Console::BOLD);
           $message = ExitCode::getReason(ExitCode::DATAERR);
           $code = ExitCode::DATAERR;
           return $code; 
        } 
    }
}
