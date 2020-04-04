<?php
namespace SimpleCarousel\Site\BlockLayout;

use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Api\Representation\SitePageRepresentation;
use Omeka\Api\Representation\SitePageBlockRepresentation;
use Omeka\Site\BlockLayout\AbstractBlockLayout;
use Zend\View\Renderer\PhpRenderer;

use Zend\Form\FormElementManager;

use SimpleCarousel\Form\CarouselBlockForm;

class Carousel extends AbstractBlockLayout
{
	/**
     * @var FormElementManager
     */
    protected $formElementManager;

    /**
     * @var array
     */
	protected $defaultSettings = [];
	
    /**
     * @param FormElementManager $formElementManager
     * @param array $defaultSettings
     */
    public function __construct(FormElementManager $formElementManager, array $defaultSettings)
    {
        $this->formElementManager = $formElementManager;
        $this->defaultSettings = $defaultSettings;
    }

	public function getLabel() {
		return 'SimpleCarousel';
	}

	public function form(PhpRenderer $view, SiteRepresentation $site,
        SitePageRepresentation $page = null, SitePageBlockRepresentation $block = null
    ) {
		$form = $this->formElementManager->get(CarouselBlockForm::class);
		$data = $block
			? $block->data() + $this->defaultSettings
			: $this->defaultSettings;
		$form->setData([
			'o:block[__blockIndex__][o:data][height]' => $data['height'],
			'o:block[__blockIndex__][o:data][duration]' => $data['duration'],
			'o:block[__blockIndex__][o:data][perPage]' => $data['perPage'],
			'o:block[__blockIndex__][o:data][loop]' => $data['loop'],
			'o:block[__blockIndex__][o:data][draggable]' => $data['draggable'],
			'o:block[__blockIndex__][o:data][title]' => $data['title'],
			'o:block[__blockIndex__][o:data][autoSlide]' => $data['autoSlide'],
			'o:block[__blockIndex__][o:data][autoSlideInt]' => $data['autoSlideInt'],
			'o:block[__blockIndex__][o:data][wrapStyle]' => $data['wrapStyle'],
			'o:block[__blockIndex__][o:data][imgStyle]' => $data['imgStyle'],
			'o:block[__blockIndex__][o:data][ui_background]' => $data['ui_background'],
			'o:block[__blockIndex__][o:data][yearSlider]' => $data['yearSlider'],
			'o:block[__blockIndex__][o:data][startYear]' => $data['startYear'],
			'o:block[__blockIndex__][o:data][endYear]' => $data['endYear'],
		]);
		$form->prepare();

		$html = '';
		$html .= $view->blockAttachmentsForm($block);
		$html .= '<a href="#" class="collapse" aria-label="collapse"><h4>' . $view->translate('Options'). '</h4></a>';
		$html .= '<div class="collapsible" style="padding-top:6px;">';
		$html .= $view->formCollection($form);
        $html .= '</div>';
		return $html;
    }

	public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
	{
		$attachments = $block->attachments();
        if (!$attachments) {
            return '';
		}

		$urls = [];

		foreach ($attachments as $attachment)
		{
			foreach($attachment->item()->media() as $media)
			{
				$mediaType = $media->mediaType();
				$mediaRenderer = $media->renderer();
				if ((strpos($mediaType, 'image/') !== false) || (strpos($mediaRenderer, 'youtube') !== false)) {
					array_push($urls, $media->thumbnailUrl('large'));
				}
			}
		}

		$partialLayout = 'common/block-layout/simple-carousel';

		if ($block->dataValue('yearSlider') == true) {
			$partialLayout = 'common/block-layout/year-slider';
		}
		
		return $view->partial($partialLayout, [
			'height' => $block->dataValue('height'),
			'duration' => $block->dataValue('duration'),
			'perPage' => $block->dataValue('perPage'),
			'loop' => $block->dataValue('loop'),
			'draggable' => $block->dataValue('draggable'),
			'title' => $block->dataValue('title'),
			'urls' => $urls,
			'autoSlide' => $block->dataValue('autoSlide'),
			'autoSlideInt' => $block->dataValue('autoSlideInt'),
			'wrapStyle' => $block->dataValue('wrapStyle'),
			'imgStyle' => $block->dataValue('imgStyle'),
			'ui_background' => $block->dataValue('ui_background'),
			'yearSlider' => $block->dataValue('yearSlider'),
			'startYear' => $block->dataValue('startYear'),
			'endYear' => $block->dataValue('endYear'),
			'attachments' => $attachments,
		]);
	}
}
