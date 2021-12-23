<?php

namespace App\Form;

use App\Entity\Postfix\Domain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewDomainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('domain', TextType::class, ['help' => 'john@domain.tld', 'attr' => ['class' => 'input']])
            ->add('description', TextType::class, ['help' => 'Description of your domain', 'attr' => ['class' => 'input']])
            ->add('nb_aliases', IntegerType::class, ['help' => 'Maximum aliases allowed', 'attr' => ['class' => 'input']])
            ->add('nb_mailboxes', IntegerType::class, ['help' => 'Maximum mailboxes allowed', 'attr' => ['class' => 'input']])
            ->add('maxquota', IntegerType::class, ['help' => 'Maximum Quota in Bytes', 'label' => 'Quota', 'attr' => ['class' => 'input']])
            ->add('backupMx', CheckboxType::class, ['label' => 'Activate MX Backup', 'attr' => ['class' => 'input']])
            ->add('is_active', CheckboxType::class, ['label' => 'should be activate ?', 'attr' => ['class' => 'input']])
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'button']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Domain::class
        ]);
    }
}
