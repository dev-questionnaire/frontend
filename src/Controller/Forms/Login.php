<?php
declare(strict_types=1);

namespace App\Controller\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class Login extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'email@example.com']])
            ->add('password', PasswordType::class)
            ->add('_remember_me', CheckboxType::class, ['required' => false])
            ->add('login', SubmitType::class);
    }
}