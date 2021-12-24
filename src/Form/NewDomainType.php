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
            ->add('domain', TextType::class, ['help' => 'domain.tld', 'attr' => ['class' => 'input']])
            ->add('description', TextType::class, ['help' => 'Description of your domain', 'attr' => ['class' => 'input']])
            ->add('nb_aliases', IntegerType::class, ['help' => 'Maximum aliases allowed (0 = ∞)', 'attr' => ['class' => 'input'], 'data' => '0'])
            ->add('nb_mailboxes', IntegerType::class, ['help' => 'Maximum mailboxes allowed (0 = ∞)', 'attr' => ['class' => 'input'], 'data' => '0'])
            ->add('maxquota', IntegerType::class, ['help' => 'Maximum Quota in Bytes (0 = ∞)', 'label' => 'Quota', 'attr' => ['class' => 'input'], 'data' => '0'])
            ->add('backupMx', CheckboxType::class, ['label' => 'Activate MX Backup', 'attr' => ['class' => 'input'], 'required' => false, 'data' => false])
            ->add('is_active', CheckboxType::class, ['label' => 'should be activate ?', 'attr' => ['class' => 'input'], 'required' => false, 'data' => true])
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
