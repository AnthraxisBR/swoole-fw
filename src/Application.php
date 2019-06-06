<?php
namespace AnthraxisBR\SwooleFW;

/**
 * TODO:: otimizar a conexão com o banco e verificar cache
 * TODO:: Organizar o código direito e comentar
 * TODO:: Melhorar comunicaço com docrtrine
 * TODO:: Verificar melhor a herança das classes
 * TODO:: Implementar PHPUNit
 * TODO::
 */
use AnthraxisBR\SwooleFW\builder\Builder;
use AnthraxisBR\SwooleFW\http\Request;
use AnthraxisBR\SwooleFW\tasks\Tasks;

class Application
{

    public $request;

    public $response;

    public $servers = [];

    public $taskResponse;

    private $protocol;

    /**
     * Irá receber a instancia de server, e as instancia da requeste resposne do Swoole
     * Irá chamar uma instancia de wrapper para gerenciar a request e reponse juntos
     * Retorna a própria instancia da classe
     * @param $server
     * @param Request $request
     * @param $response
     * @return $this
     */
    public function appendConfig($server, Request $request, $response)
    {
        /**
         * Set request Protocol
         */
        $this->setProtocol($protocol = $request->swoole_request->server['server_protocol']);

        /**
         * Set server instances on attribute 'SERVER', it will be used to run the server
         */
        $this->servers[$this->getServerId($server)] = Builder::wrapper($server, $request, $response);

        return $this;
    }

    /**
     * É usado para identificar qual a herança de server a Application recebeu
     * @param $server
     * @return string
     */
    public function getServerId($server) : string
    {
        $server_exp = explode('\\',get_class($server));
        return $server_exp[count($server_exp ) - 1];
    }

    /**
     * This method will start a tarsg signed on request
     * @param $serv
     * @param $task_id
     * @param $from_id
     * @param $data
     */
    public function runSignedTask($serv, $task_id, $from_id, $data) : void
    {
        $this->taskResponse = Tasks::run($serv, $task_id, $from_id, $data);
    }

    /**
     * Will run the server when it configured
     * @return mixed
     */
    public function run()
    {
        if($this->isHttpProtocol()){
            return $this->servers['SwooleServer']->process();
        }
    }

    /**
     * @param string $protocol
     */
    public function setProtocol(string $protocol = '') : void
    {
        $this->protocol = (string) $protocol;
    }

    /**
     * Check if request has http protocol
     * @return bool
     */
    public function isHttpProtocol() : bool
    {
        return (bool) explode('/', $this->protocol)[0] == 'HTTP';
    }

}