<?php

declare(strict_types=1);

namespace App\Console\Commands\Core;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'env:set')]
class EnvSetCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:set
                    {key : The environment variable key}
                    {value : The environment variable value}
                    {--show : Display the value instead of modifying files}
                    {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set an environment variable';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = $this->argument('key');
        $value = $this->argument('value');

        if ($this->option('show')) {
            return $this->line('<comment>'.$key.'='.$value.'</comment>');
        }

        if (! $this->setEnvValue($key, $value)) {
            return;
        }

        $this->laravel['config'][$key] = $value;

        $this->components->info('Environment variable set successfully.');
    }

    /**
     * Set the environment variable in the environment file.
     *
     * @param  string  $key
     * @param  string  $value
     * @return bool
     */
    protected function setEnvValue($key, $value)
    {
        $currentValue = env($key);

        if (! empty($currentValue) && mb_strlen($currentValue) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        // Process the value for specific cases before writing to the .env file
        $value = $this->processEnvValue($key, $value);

        // Ensure the value is wrapped in double quotes
        $value = $this->ensureValueIsWrappedInQuotes($value);

        if (! $this->writeNewEnvironmentFileWith($key, $value)) {
            return false;
        }

        return true;
    }

    /**
     * Process the value for specific environment variables before setting them.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string
     */
    protected function processEnvValue($key, $value)
    {
        $appName = env('APP_NAME') ?: config('app.name');
        $mailUsername = env('MAIL_USERNAME') ?: config('mail.mailers.smtp.username');

        if ($key === 'MAIL_FROM_NAME' && $appName === $value) {
            return '${APP_NAME}';
        }

        if ($key === 'MAIL_FROM_ADDRESS' && $mailUsername === $value) {
            return '${MAIL_USERNAME}';
        }

        return $value;
    }

    /**
     * Ensure that the value is wrapped in double quotes.
     *
     * @param  string  $value
     * @return string
     */
    protected function ensureValueIsWrappedInQuotes($value)
    {
        // Return value wrapped in double quotes if not already wrapped
        if (mb_strpos($value, '"') !== 0 || mb_strrpos($value, '"') !== mb_strlen($value) - 1) {
            return '"'.$value.'"';
        }

        return $value;
    }

    /**
     * Write a new environment file with the given key and value.
     *
     * @param  string  $key
     * @param  string  $value
     * @return bool
     */
    protected function writeNewEnvironmentFileWith($key, $value)
    {
        $input = file_get_contents($this->laravel->environmentFilePath());

        // Replace or add the key-value pair
        $pattern = $this->keyReplacementPattern($key);
        if (preg_match($pattern, $input)) {
            $replaced = preg_replace($pattern, $key.'='.$value, $input);
        } else {
            $replaced = $input.PHP_EOL.$key.'='.$value;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    /**
     * Get a regex pattern that will match env key with any random value.
     *
     * @param  string  $key
     * @return string
     */
    protected function keyReplacementPattern($key)
    {
        return '/^'.preg_quote($key, '/')."=(?:\".*\"|[^\"\n]*).*$/m";
    }
}
