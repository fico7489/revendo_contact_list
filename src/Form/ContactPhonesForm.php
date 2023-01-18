<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\ContactPhone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactPhonesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('contactPhones', CollectionType::class, [
            'entry_type' => ContactPhoneForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                //'label' => false
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'method' => 'POST',
        ]);
    }
}
