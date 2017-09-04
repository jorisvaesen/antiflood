<?php
namespace JorisVaesen\Antiflood\Shell;

use Cake\Mailer\Email;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use DateTime;

/**
 * MailReport shell command.
 */
class MailReportShell extends Shell
{
    public function getOptionParser()
    {
        return parent::getOptionParser()
            ->addOption('config', [
                'help' => 'Mail config name to use',
                'short' => 'c',
                'default' => 'default'
            ])
            ->addOption('days', [
                'help' => 'Amount of days in the past the report should collect until today',
                'short' => 'd',
                'default' => 7,
            ])
            ->addOption('model', [
                'help' => 'Model classname to use to fetch the logs',
                'short' => 'm',
                'default' => 'JorisVaesen/Antiflood.Antifloods',
            ])
            ->addOption('name', [
                'help' => 'Name of your app',
                'short' => 'n',
                'default' => 'CakePHP app'
            ])
            ->addArgument('to', [
                'help' => 'Email address to sent report to',
                'required' => true,
            ]);
    }

    public function initialize()
    {
        parent::initialize();
    }

    public function startup()
    {
        parent::startup();
    }

    public function main()
    {
        $to = $this->args[0];
        $name = $this->param('name');
        $table = TableRegistry::get($this->param('model'));
        $config = $this->param('config');
        $startDate = new DateTime('-' . $this->param('days') . ' days');
        $startDate->setTime(0,0,0);

        $logs = $table->find()
            ->where([
                $table->aliasField('created') . ' >=' => $startDate
            ])
            ->groupBy('identifier')
            ->toArray();

        $email = new Email($config);
        $email
            ->setTo($to)
            ->setSubject('Antiflood Report')
            ->setTemplate('JorisVaesen/Antiflood.report')
            ->setLayout('JorisVaesen/Antiflood.report')
            ->setViewVars(compact('logs', 'name'))
            ->setEmailFormat('html')
            ->send();
    }
}
