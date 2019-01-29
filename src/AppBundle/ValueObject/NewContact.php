<?php

namespace AppBundle\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;

class NewContact
{
    /**
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "email_too_short",
     * )
     * @Assert\Email(
     *     message = "invalid_email"
     * )
     */
    protected $email;

    /**
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "text_too_short",
     * )
     */
    protected $text;

    public function __construct($email, $text)
    {
        $this->email = $email;
        $this->text = $text;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getText()
    {
        return $this->text;
    }
}