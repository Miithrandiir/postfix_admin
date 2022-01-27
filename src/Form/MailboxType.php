<?php

namespace App\Form;

use App\Entity\Postfix\Domain;
use App\Entity\Postfix\Mailbox;
use App\Repository\DomainRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailboxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('domain', EntityType::class, [
                'class' => Domain::class,
                'query_builder' => function (DomainRepository $er) use ($options) {
                    return $er->findDomainByIdQueryBuilder($options['user_id']);
                },
                'choice_label' => 'domain',
                'choice_value' => 'id',
                'attr' => ['class' => 'input']

            ])
            ->add('username', TextType::class, ['required' => true, 'attr' => ['class' => 'input']]);

        if ($options['is_edit']) {
            $builder->add('password', PasswordType::class, ['required' => false, 'attr' => ['class' => 'input'], 'empty_data' => "", 'help' => "Leave empty if no change"]);
        } else {
            $builder
                ->add('password', PasswordType::class, ['required' => true, 'attr' => ['class' => 'input']]);
        }

        $builder
            ->add('name', TextType::class, ['required' => false, 'attr' => ['class' => 'input']])
            ->add('firstname', TextType::class, ['required' => false, 'attr' => ['class' => 'input']])
            ->add('mailDir', TextType::class, ['required' => true, 'attr' => ['class' => 'input']])
            ->add('quota', NumberType::class, ['required' => true, 'attr' => ['class' => 'input']])
            ->add('active', CheckboxType::class, ['attr' => ['class' => 'input']])
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'button']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Mailbox::class,
            'user_id' => 0,
            'is_edit' => false,
        ]);
    }

}