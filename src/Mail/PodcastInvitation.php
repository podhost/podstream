<?php
namespace Podhost\Podstream\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Podhost\Podstream\PodcastInvitation as PodcastInvitationModel;

class PodcastInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The podcast invitation instance.
     *
     * @var \Podhost\Podstream\PodcastInvitation
     */
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @param  \Podhost\Podstream\PodcastInvitation  $invitation
     * @return void
     */
    public function __construct(PodcastInvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('podstream::mail.podcast-invitation', ['acceptUrl' => URL::signedRoute('podcast-invitations.accept', [
            'invitation' => $this->invitation,
        ])])->subject(__('You have been invited to manage the Podcast on Podhost!'));
    }
}
