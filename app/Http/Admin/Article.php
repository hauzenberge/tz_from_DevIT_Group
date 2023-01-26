<?php

namespace App\Http\Admin;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;

/**
 * Class Article
 *
 * @property \App\Models\Article $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Article extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation()->setPriority(100)->setIcon('fa fa-lightbulb-o');
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay($payload = [])
    {
        // dd(Auth::user()->role);
        $columns = [
            AdminColumn::text('id', '#')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::link('title', 'Title', 'created_at')
                ->setSearchCallback(function ($column, $query, $search) {
                    if (Auth::user()->role == "Author") {
                        return $query
                            ->where('user_id', Auth::user()->id)
                            ->orWhere('title', 'like', '%' . $search . '%')
                            ->orWhere('text', 'like', '%' . $search . '%')
                            ->orWhere('created_at', 'like', '%' . $search . '%');
                    } else  return $query
                        ->orWhere('title', 'like', '%' . $search . '%')
                        ->orWhere('text', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%');
                })
                ->setOrderable(function ($query, $direction) {
                    $query->orderBy('created_at', $direction);
                }),
           // AdminColumn::boolean('name', 'On'),
            AdminColumn::text('created_at', 'Created / updated', 'updated_at')
                ->setWidth('160px')
                ->setOrderable(function ($query, $direction) {
                    $query->orderBy('updated_at', $direction);
                })
                ->setSearchable(false),
        ];

        $display = AdminDisplay::datatables()
            ->setName('firstdatatables')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-center');

        $display->setColumnFilters([
            AdminColumnFilter::select()
                ->setModelForOptions(\App\Models\Article::class, 'name')
                ->setLoadOptionsQueryPreparer(function ($element, $query) {
                    return $query;
                })
                ->setDisplay('name')
                ->setColumnName('name')
                ->setPlaceholder('All names'),
        ]);
        $display->getColumnFilters()->setPlacement('card.heading');
        return $display;
    }

    /**
     * @param int|null $id
     * @param array $payload
     *
     * @return FormInterface
     */
    public function onEdit($id = null, $payload = [])
    {
        $form = AdminForm::card()->addBody([
            AdminFormElement::columns()->addColumn([
                AdminFormElement::text('title', 'Title')
                    ->required(),
                AdminFormElement::wysiwyg('text', 'Text')
                    ->required(),
                AdminFormElement::html('<hr>'),
                AdminFormElement::text('user_id', 'User ID')
                    //->value(Auth::user()->id)
                    ->required(),
                AdminFormElement::html('<hr>'),
                AdminFormElement::datetime('created_at')
                    ->setVisible(true)
                    ->setReadonly(false),
                AdminFormElement::html('last AdminFormElement without comma')
            ], ),
        ]);

        $form->getButtons()->setButtons([
            'save'  => new Save(),
            'save_and_close'  => new SaveAndClose(),
            'save_and_create'  => new SaveAndCreate(),
            'cancel'  => (new Cancel()),
        ]);

        return $form;
    }

    /**
     * @return FormInterface
     */
    public function onCreate($payload = [])
    {
        return $this->onEdit(null, $payload);
    }

    /**
     * @return bool
     */
    public function isDeletable(Model $model)
    {
        return true;
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}
