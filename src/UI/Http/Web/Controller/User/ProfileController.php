<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Controller\User;

use App\UI\Http\Web\Controller\Shared\AbstractRenderController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ProfileController extends AbstractRenderController
{
    /**
     * @Route(
     *     "/user/profile",
     *     name="profile",
     *     methods={"GET"}
     * )
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function profile(): Response
    {
        return $this->render('User/Profile/index.html.twig');
    }
}
