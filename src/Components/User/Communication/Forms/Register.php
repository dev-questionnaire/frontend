<?php
declare(strict_types=1);

namespace App\Components\User\Communication\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class Register extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'm.muster@nexus-umited.com']])
            ->add('password', PasswordType::class)
            ->add('verificationPassword', PasswordType::class)
            ->add('register', SubmitType::class);
    }
}