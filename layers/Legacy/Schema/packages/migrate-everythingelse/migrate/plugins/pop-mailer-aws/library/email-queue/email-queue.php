<?php 

class PoP_Mailer_EmailQueueImpl extends PoP_Mailer_EmailQueueBase
{
    protected $queue;

    public function __construct()
    {
        parent::__construct();
        $this->queue = array();
    }

    public function getQueue()
    {
        return $this->queue;
    }

    public function enqueueEmail($to, $subject, $msg, $headers)
    {

        // Group the emails by header, so they are all sent all together in the same file
        // Gravity Forms sends the headers as an array:
        // array(
        //  'From' => $from,
        //  'Content-type' => $content_type,
        // )
        // Transform the array into a string, with the proper format
        if (is_array($headers)) {
            $headers = implode('\r\n', array_values($headers));
        }

        if (!$this->queue[$headers]) {
            $this->queue[$headers] = array();
        }

        // $to must be an array!
        if (!is_array($to)) {
            $to = explode(',', $to);
        }
        // Trim the values
        $to = array_map(trim(...), $to);
        $this->queue[$headers][] = array(
            'to' => $to,
            'subject' => $subject,
            'message' => $msg,
        );
    }
}

/**
 * Initialization
 */
new PoP_Mailer_EmailQueueImpl();
