<?php

namespace AnthraxisBR\SwooleFW\routing;


use AnthraxisBR\SwooleFW\database\Entitites;
use AnthraxisBR\SwooleFW\graphql\GraphQL;
use AnthraxisBR\SwooleFW\http\Request;
use AnthraxisBR\SwooleFW\providers\Providers;
use AnthraxisBR\SwooleFW\graphql\GraphQLYamlReader;
use Illuminate\Support\Str;
use ReflectionFunction;
use ReflectionMethod;


class Router
{

    private $method;

    private $uri;

    private $remote_addr;

    private $protocol;

    public $application;

    public $response;

    public $parameters;

    public $providers = [];

    private $query;

    /**
     * @var Wrapper
     */
    public $wrapper;

    public $route = [];

    private $invokable_function;

    private $classname;

    public function __construct(Wrapper $wrapper)
    {
        $this->response = new \stdClass();
        $this->wrapper = $wrapper;


        $this->build();
    }

    public function build()
    {
        $this->method = $this->wrapper->getRequestMethod();
        $this->uri = $this->wrapper->getRequestUri();
        $this->remote_addr = $this->wrapper->getRequestRemoteAddr();
        $this->protocol = $this->wrapper->getRequestServerProtocol();

        $this->setProviders();

        $this->setRunningRoute();

        $this->applyHttpMethodRules();

        $this->route = (object) $this->route;

        $this->superCall();
    }

    private function applyHttpMethodRules()
    {
        if($this->method == 'POST'){
            $this->applyHttpMethodPostRules();
        }
    }

    private function applyHttpMethodPostRules()
    {
        $this->implementsGraphqlroute();
    }

    private function implementsGraphqlroute()
    {
        if($this->hasGraphQLQueryBody()) {
            $this->query = $this->wrapper->getPostBody()->query;
            $GraphQLRoutesYaml = new GraphQLYamlReader();
            $this->route = $GraphQLRoutesYaml->getRoute($this->route);
        }
    }

    private function setRunningRoute() : void
    {

        $uri_exp = explode('/', $this->uri);

        if(isset($uri_exp[0]) and $uri_exp[0] == ''){
            unset($uri_exp[0]);
            $uri_exp = array_values($uri_exp);
        }

        $RoutesYaml = new RoutesYamlReader();
        $this->route = $RoutesYaml->getRoute($uri_exp);

        $this->route['function'] = $this->route['methods'][$this->method];

    }
    /**
     *
     */
    private function superCall() : void
    {
        $this->buildInvokableFunction();

        $this->application = new $this->classname();

        $this->runProviders();

        /**
         * Call action
         */
        $this->response = call_user_func_array([$this->application,$this->route->function],$this->providers);
    }

    /**
     *
     */
    private function runProviders() : void
    {
        /**
         * Appending providers from action function parameters
         */
        foreach ($this->parameters as $param){

            /**
             * Use a reference provided inside all provided classes
             */
            $ref = $param->getClass()->name::getInjectReference();

            /**
             * Some providers cannot be used without an entity instance
             */
            if(isset($this->application->providers['entity'])){
                $this->application->appendProvided($this->providers['action_providers'][$ref]->getInstance($route = $this, $swoole_request = $this->wrapper->getRequest(), $entity = $this->application->providers['entity']));
            }else{
                $this->application->appendProvided($this->providers['action_providers'][$ref]->getInstance($route = $this, $swoole_request = $this->wrapper->getRequest()));
            }
        }
        /**
         * Applying fixed providers, can be mandatory
         */
        foreach ($this->fixedProviders() as $provider){
            $this->application->appendFixedProvided($provider->getInstance($route = $this, $swoole_request = $this->wrapper->getRequest(), null, true));
        }

        $fixed_providers = $this->application->providers['fixed'];

        unset($this->application->providers['fixed']);

        $this->providers = $this->application->providers;

        unset($this->application->providers);

        $this->application->providers = $fixed_providers;
    }

    /**
     * @return string
     */
    private function buildInvokableFunction() : void
    {
        $namespace = '\App\actions\\';
        $this->classname = $namespace . $this->route->action . 'Action';
        $this->invokable_function = $this->classname  . '@' . $this->route->function;
        $this->invokable_function  =str_replace('()','',$this->invokable_function);
        $this->parameters = $this->getParametersFromInvokableClassFunction($this->invokable_function);
    }

    /**
     * Return all fixed providers available
     * @return array
     */
    public function fixedProviders() : array
    {
        return $this->providers['fixed_providers'];
    }

    /**
     * Define providers from primary instance into router provider attribute
     */
    private function setProviders()
    {
        $providers = new Providers($this);
        $this->providers = $providers->getProviders();
    }

    /**
     * Check a request is to append graphql provider
     * @return bool
     */
    public function hasGraphQLQueryBody()
    {
        return isset($this->wrapper->getPostBody()->query);
    }

    /**
     * @param $invokable_function
     * @return \ReflectionParameter[]
     * @throws \ReflectionException
     */
    protected function getParametersFromInvokableClassFunction($invokable_function)
    {
        $exp = explode('@',$invokable_function);
        $ReflectionMethod = new ReflectionMethod($exp[0], $exp[1]);
        return $ReflectionMethod->getParameters();
    }

    /**
     * @return false|string
     */
    public function getResponse()
    {
        return json_encode($this->response);
    }
}