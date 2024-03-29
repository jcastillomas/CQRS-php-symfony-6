<?php

declare(strict_types=1);

namespace App\Tests\EndToEnd\UI\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class SignUpControllerTest extends WebTestCase
{
    use CreateUserTrait;
    /**
     * @test
     *
     * @group e2e
     */
    public function sign_up_page_form_format(): void
    {
        $client = self::createClient([
            'environment' => 'test',
            'debug'       => false
        ]);
        $client->catchExceptions(false);

        $crawler = $client->request('GET', '/sign-up');

        $this->assertSame(1, $crawler->filter('label:contains("Email")')->count());
        $this->assertSame(1, $crawler->selectButton('Send')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function sign_up_form_create_user_success(): void
    {
        $crawler = $this->createUser($email = 'websignupcontrollertest1@users.com');

        self::assertSame(1, $crawler->filter('html:contains("Hello ' . $email . '")')->count());
        self::assertSame(1, $crawler->filter('html:contains("Your id is ")')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function sign_up_form_create_user_invalid_email(): void
    {
        $crawler = $this->createUser('signupcontrollertest2@users');

        self::assertSame(1, $crawler->filter('html:contains("Not a valid email")')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function sign_up_form_create_user_with_email_already_taken(): void
    {
        $crawler = $this->createUser('websignupcontrollertest1@users.com');

        self::assertSame(1, $crawler->filter('html:contains("Email already registered.")')->count());
    }

    private function createUser(string $email, string $password = 'password'): Crawler
    {
        self::ensureKernelShutdown();
        $client = self::createClient([
            'environment' => 'test',
            'debug'       => false
        ]);
        $client->catchExceptions(false);

        $crawler = $client->request('GET', '/sign-up');

        $form = $crawler->selectButton('Send')->form();

        $form->get('email')->setValue($email);
        $form->get('password')->setValue($password);

        $crawler = $client->submit($form);

        return $crawler;
    }
}
