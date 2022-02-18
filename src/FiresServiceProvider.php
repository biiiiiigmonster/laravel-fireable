<?php


namespace BiiiiiigMonster\Fires;


use BiiiiiigMonster\Fires\Console\FireAttributesMakeCommand;
use Illuminate\Support\ServiceProvider;

class FiresServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(FireAttributesMakeCommand::class);
    }
}