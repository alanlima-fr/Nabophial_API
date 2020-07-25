<?php

namespace App\Representation;

use LogicException;
use Pagerfanta\Pagerfanta;

class Pagination
{
    public $data;
    public $meta;

    public function __construct(Pagerfanta $data)
    {
        $this->data = $data->getCurrentPageResults();

        $this->addMeta('current_items', count($data->getCurrentPageResults()));
        $this->addMeta('total_items', $data->getNbResults());
        $this->addMeta('current_page', $data->getCurrentPage());
        $this->addMeta('total_number_of_page', $data->getNbpages());
        $this->addMeta('limit', $data->getMaxPerPage());
    }

    public function addMeta($name, $value): void
    {
        if (isset($this->meta[$name])) {
            throw new LogicException(sprintf('This meta already exists. You are trying to override this meta, use the setMeta method instead for the %s meta.', $name));
        }

        $this->setMeta($name, $value);
    }

    public function setMeta($name, $value): void
    {
        $this->meta[$name] = $value;
    }
}
