<?php namespace Fakers\Score;

use Services\Config\Facade as Config;
use Services\Twitter\Object\Users;
use Fakers\Score\Object\Language as LanguageObject;
use Fakers\Score\Object\Languages;
use Exception\FakerScoreException;
use Fakers\Score\Object\UserGroup;

class Language
{
	public function getLanguageStats(UserGroup $followerGroup)
	{
		$languages = Config::make()->get('language.language_list');
		
		if ($followerGroup->count() >= 1) {

			foreach ($followerGroup as $followers) {
				
				if ($followers->count() >= 1) {

					foreach ($followers as $follower) {
						
						foreach ($languages as $k => $l) {

							if ($k == substr($follower->language, 0, 2)) {
								$languageArray[$k]['code'] = $k;
								$languageArray[$k]['name'] = $l->name;
								$languageArray[$k]['count'] = $langs[$k]['count'] += 1; 
							}
						}
					}
				}
			}
			
			$languageArray = $this->reOrderLanguages($languageArray);

			return $this->buildLanguageCollection($languageArray);
		}

		throw new FakerScoreException('Could not build Language Stats');
	}
	
	public function buildLanguageCollection(array $languages)
	{
		if (!empty($languages)) {
			foreach ($languages as $k => $language) {
				$languageArray[] = new LanguageObject($language['code'], $language['name'], $language['count']);
			}

			return new Languages($languageArray);
		}
		
		throw new FakerScoreException('Could not build Language Stats, no languages found');
	}

	protected function reOrderLanguages($languages)
	{
		if (!empty($languages)) {
			usort($languages, [$this, 'orderLanguages']);
		}
		
		return $languages;
	}

	protected function orderLanguages($a, $b)
	{
		if ($a['count'] == $b['count']) {
			return 0;
		}

		return ($a['count'] < $b['count']) ? 1 : -1;
	}
}