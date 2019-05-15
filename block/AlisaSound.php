<?php
/**
 * User: MaxM18
 */

namespace alisa\block;

class AlisaSound
{
    const S_EFFECT_BEHIND_THE_WALL = '<speaker effect="behind_the_wall">';
    const S_EFFECT_HAMSTER = '<speaker effect="hamster">';
    const S_EFFECT_MEGAPHONE = '<speaker effect="megaphone">';
    const S_EFFECT_PITCH_DOWN = '<speaker effect="pitch_down">';
    const S_EFFECT_PSYCHODELIC = '<speaker effect="psychodelic">';
    const S_EFFECT_PULSE = '<speaker effect="pulse">';
    const S_EFFECT_TRAIN_ANNOUNCE = '<speaker effect="train_announce">';
    const S_EFFECT_END = '<speaker effect="-">';

    protected $standardParam = [
        [
            'key' => '#$game_win$#',
            'sounds' => [
                '<speaker audio="alice-sounds-game-win-1.opus">',
                '<speaker audio="alice-sounds-game-win-2.opus">',
                '<speaker audio="alice-sounds-game-win-3.opus">',
            ]
        ],
        [
            'key' => '#$game_loss$#',
            'sounds' => [
                '<speaker audio="alice-sounds-game-loss-1.opus">',
                '<speaker audio="alice-sounds-game-loss-2.opus">',
                '<speaker audio="alice-sounds-game-loss-3.opus">',
            ]
        ],
        [
            'key' => '#$game_boot$#',
            'sounds' => [
                '<speaker audio="alice-sounds-game-boot-1.opus">',
            ]
        ],
        [
            'key' => '#$game_coin$#',
            'sounds' => [
                '<speaker audio="alice-sounds-game-8-bit-coin-1.opus">',
                '<speaker audio="alice-sounds-game-8-bit-coin-2.opus">',
            ]
        ],
        [
            'key' => '#$game_ping$#',
            'sounds' => [
                '<speaker audio="alice-sounds-game-ping-1.opus">',
            ]
        ],
        [
            'key' => '#$game_fly$#',
            'sounds' => [
                '<speaker audio="alice-sounds-game-8-bit-flyby-1.opus">',
            ]
        ],
        [
            'key' => '#$game_gun$#',
            'sounds' => [
                '<speaker audio="alice-sounds-game-8-bit-machine-gun-1.opus',
            ]
        ],
        [
            'key' => '#$game_phone$#',
            'sounds' => [
                '<speaker audio="alice-sounds-game-8-bit-phone-1.opus">',
            ]
        ],
        [
            'key' => '#$game_powerup$#',
            'sounds' => [
                '<speaker audio="alice-sounds-game-powerup-1.opus">',
                '<speaker audio="alice-sounds-game-powerup-2.opus">',
            ]
        ],

        [
            'key' => '#$nature_wind$#',
            'sounds' => [
                '<speaker audio="alice-sounds-nature-wind-1.opus">',
                '<speaker audio="alice-sounds-nature-wind-2.opus">',
            ]
        ],
        [
            'key' => '#$nature_thunder$#',
            'sounds' => [
                '<speaker audio="alice-sounds-nature-thunder-1.opus">',
                '<speaker audio="alice-sounds-nature-thunder-2.opus">',
            ]
        ],
        [
            'key' => '#$nature_jungle$#',
            'sounds' => [
                '<speaker audio="alice-sounds-nature-jungle-1.opus">',
                '<speaker audio="alice-sounds-nature-jungle-2.opus">',
            ]
        ],
        [
            'key' => '#$nature_rain$#',
            'sounds' => [
                '<speaker audio="alice-sounds-nature-rain-1.opus">',
                '<speaker audio="alice-sounds-nature-rain-2.opus">',
            ]
        ],
        [
            'key' => '#$$#',
            'sounds' => [
                '<speaker audio="alice-sounds-nature-forest-1.opus">',
                '<speaker audio="alice-sounds-nature-forest-2.opus">',
            ]
        ],
        [
            'key' => '#$nature_sea$#',
            'sounds' => [
                '<speaker audio="alice-sounds-nature-sea-1.opus">',
                '<speaker audio="alice-sounds-nature-sea-2.opus">',
            ]
        ],
        [
            'key' => '#$nature_fire$#',
            'sounds' => [
                '<speaker audio="alice-sounds-nature-fire-1.opus">',
                '<speaker audio="alice-sounds-nature-fire-2.opus">',
            ]
        ],
        [
            'key' => '#$nature_stream$#',
            'sounds' => [
                '<speaker audio="alice-sounds-nature-stream-1.opus">',
                '<speaker audio="alice-sounds-nature-stream-2.opus">',
            ]
        ],
        [
            'key' => '#$thing_chainsaw$#',
            'sounds' => [
                '<speaker audio="alice-sounds-things-chainsaw-1.opus">',
                '<speaker audio="alice-sounds-things-explosion-1.opus">',
                '<speaker audio="alice-sounds-things-water-3.opus">',
                '<speaker audio="alice-sounds-things-water-1.opus">',
                '<speaker audio="alice-sounds-things-water-2.opus">',
                '<speaker audio="alice-sounds-things-switch-1.opus">',
                '<speaker audio="alice-sounds-things-switch-2.opus">',
                '<speaker audio="alice-sounds-things-gun-1.opus">',
                '<speaker audio="alice-sounds-transport-ship-horn-1.opus">',
                '<speaker audio="alice-sounds-transport-ship-horn-2.opus">',
                '<speaker audio="alice-sounds-things-door-1.opus">',
                '<speaker audio="alice-sounds-things-door-2.opus">',
                '<speaker audio="alice-sounds-things-glass-2.opus">',
                '<speaker audio="alice-sounds-things-bell-1.opus">',
                '<speaker audio="alice-sounds-things-bell-2.opus">',
                '<speaker audio="alice-sounds-things-car-1.opus">',
                '<speaker audio="alice-sounds-things-car-2.opus">',
                '<speaker audio="alice-sounds-things-sword-2.opus">',
                '<speaker audio="alice-sounds-things-sword-1.opus">',
                '<speaker audio="alice-sounds-things-sword-3.opus">',
                '<speaker audio="alice-sounds-things-siren-1.opus">',
                '<speaker audio="alice-sounds-things-siren-2.opus">',
                '<speaker audio="alice-sounds-things-old-phone-1.opus">',
                '<speaker audio="alice-sounds-things-old-phone-2.opus">',
                '<speaker audio="alice-sounds-things-glass-1.opus">',
                '<speaker audio="alice-sounds-things-construction-2.opus">',
                '<speaker audio="alice-sounds-things-construction-1.opus">',
                '<speaker audio="alice-sounds-things-phone-1.opus">',
                '<speaker audio="alice-sounds-things-phone-2.opus">',
                '<speaker audio="alice-sounds-things-phone-3.opus">',
                '<speaker audio="alice-sounds-things-phone-4.opus">',
                '<speaker audio="alice-sounds-things-phone-5.opus">',
                '<speaker audio="alice-sounds-things-toilet-1.opus">',
                '<speaker audio="alice-sounds-things-cuckoo-clock-2.opus">',
                '<speaker audio="alice-sounds-things-cuckoo-clock-1.opus">',
            ]
        ],
        [
            'key' => '#$animals_all$#',
            'sounds' => [
                '<speaker audio="alice-sounds-animals-wolf-1.opus">',
                '<speaker audio="alice-sounds-animals-crow-1.opus">',
                '<speaker audio="alice-sounds-animals-crow-2.opus">',
                '<speaker audio="alice-sounds-animals-cow-1.opus">',
                '<speaker audio="alice-sounds-animals-cow-2.opus">',
                '<speaker audio="alice-sounds-animals-cow-3.opus">',
                '<speaker audio="alice-sounds-animals-cat-1.opus">',
                '<speaker audio="alice-sounds-animals-cat-2.opus">',
                '<speaker audio="alice-sounds-animals-cat-3.opus">',
                '<speaker audio="alice-sounds-animals-cat-4.opus">',
                '<speaker audio="alice-sounds-animals-cat-5.opus">',
                '<speaker audio="alice-sounds-animals-cuckoo-1.opus">',
                '<speaker audio="alice-sounds-animals-chicken-1.opus">',
                '<speaker audio="alice-sounds-animals-lion-1.opus">',
                '<speaker audio="alice-sounds-animals-lion-2.opus">',
                '<speaker audio="alice-sounds-animals-horse-1.opus">',
                '<speaker audio="alice-sounds-animals-horse-2.opus">',
                '<speaker audio="alice-sounds-animals-horse-galloping-1.opus">',
                '<speaker audio="alice-sounds-animals-horse-walking-1.opus">',
                '<speaker audio="alice-sounds-animals-frog-1.opus">',
                '<speaker audio="alice-sounds-animals-seagull-1.opus">',
                '<speaker audio="alice-sounds-animals-monkey-1.opus">',
                '<speaker audio="alice-sounds-animals-sheep-1.opus">',
                '<speaker audio="alice-sounds-animals-sheep-2.opus">',
                '<speaker audio="alice-sounds-animals-rooster-1.opus">',
                '<speaker audio="alice-sounds-animals-elephant-1.opus">',
                '<speaker audio="alice-sounds-animals-elephant-2.opus">',
                '<speaker audio="alice-sounds-animals-dog-1.opus">',
                '<speaker audio="alice-sounds-animals-dog-2.opus">',
                '<speaker audio="alice-sounds-animals-dog-3.opus">',
                '<speaker audio="alice-sounds-animals-dog-4.opus">',
                '<speaker audio="alice-sounds-animals-dog-5.opus">',
                '<speaker audio="alice-sounds-animals-owl-1.opus">',
                '<speaker audio="alice-sounds-animals-owl-2.opus">',
            ]
        ],
        [
            'key' => '#$human_all$#',
            'sounds' => [
                '<speaker audio="alice-sounds-human-cheer-1.opus">',
                '<speaker audio="alice-sounds-human-cheer-2.opus">',
                '<speaker audio="alice-sounds-human-kids-1.opus">',
                '<speaker audio="alice-sounds-human-walking-dead-1.opus">',
                '<speaker audio="alice-sounds-human-walking-dead-2.opus">',
                '<speaker audio="alice-sounds-human-walking-dead-3.opus">',
                '<speaker audio="alice-sounds-human-cough-1.opus">',
                '<speaker audio="alice-sounds-human-cough-2.opus">',
                '<speaker audio="alice-sounds-human-laugh-1.opus">',
                '<speaker audio="alice-sounds-human-laugh-2.opus">',
                '<speaker audio="alice-sounds-human-laugh-3.opus">',
                '<speaker audio="alice-sounds-human-laugh-4.opus">',
                '<speaker audio="alice-sounds-human-laugh-5.opus">',
                '<speaker audio="alice-sounds-human-crowd-1.opus">',
                '<speaker audio="alice-sounds-human-crowd-2.opus">',
                '<speaker audio="alice-sounds-human-crowd-3.opus">',
                '<speaker audio="alice-sounds-human-crowd-4.opus">',
                '<speaker audio="alice-sounds-human-crowd-5.opus">',
                '<speaker audio="alice-sounds-human-crowd-7.opus">',
                '<speaker audio="alice-sounds-human-crowd-6.opus">',
                '<speaker audio="alice-sounds-human-sneeze-1.opus">',
                '<speaker audio="alice-sounds-human-sneeze-2.opus">',
                '<speaker audio="alice-sounds-human-walking-room-1.opus">',
                '<speaker audio="alice-sounds-human-walking-snow-1.opus">',
            ]
        ],
        [
            'key' => '#$music_all$#',
            'sounds' => [
                '<speaker audio="alice-music-harp-1.opus">',
                '<speaker audio="alice-music-drums-1.opus">',
                '<speaker audio="alice-music-drums-2.opus">',
                '<speaker audio="alice-music-drums-3.opus">',
                '<speaker audio="alice-music-drum-loop-1.opus">',
                '<speaker audio="alice-music-drum-loop-2.opus">',
                '<speaker audio="alice-music-tambourine-80bpm-1.opus">',
                '<speaker audio="alice-music-tambourine-100bpm-1.opus">',
                '<speaker audio="alice-music-tambourine-120bpm-1.opus">',
                '<speaker audio="alice-music-bagpipes-1.opus">',
                '<speaker audio="alice-music-bagpipes-2.opus">',
                '<speaker audio="alice-music-guitar-c-1.opus">',
                '<speaker audio="alice-music-guitar-e-1.opus">',
                '<speaker audio="alice-music-guitar-g-1.opus">',
                '<speaker audio="alice-music-guitar-a-1.opus">',
                '<speaker audio="alice-music-gong-1.opus">',
                '<speaker audio="alice-music-gong-2.opus">',
                '<speaker audio="alice-music-horn-2.opus">',
                '<speaker audio="alice-music-violin-c-1.opus">',
                '<speaker audio="alice-music-violin-c-2.opus">',
                '<speaker audio="alice-music-violin-a-1.opus">',
                '<speaker audio="alice-music-violin-e-1.opus">',
                '<speaker audio="alice-music-violin-d-1.opus">',
                '<speaker audio="alice-music-violin-b-1.opus">',
                '<speaker audio="alice-music-violin-g-1.opus">',
                '<speaker audio="alice-music-violin-f-1.opus">',
                '<speaker audio="alice-music-horn-1.opus">',
                '<speaker audio="alice-music-piano-c-1.opus">',
                '<speaker audio="alice-music-piano-c-2.opus">',
                '<speaker audio="alice-music-piano-a-1.opus">',
                '<speaker audio="alice-music-piano-e-1.opus">',
                '<speaker audio="alice-music-piano-d-1.opus">',
                '<speaker audio="alice-music-piano-b-1.opus">',
                '<speaker audio="alice-music-piano-g-1.opus">',
            ]
        ],
    ];

    const S_AUDIO_GAME_BOOT = '#$game_boot$#';
    const S_AUDIO_GAME_8_BIT_COIN = '#$game_coin$#';
    const S_AUDIO_GAME_LOSS = '#$game_loss$#';
    const S_AUDIO_GAME_PING = '#$game_ping$#';
    const S_AUDIO_GAME_WIN = '#$game_win$#';
    const S_AUDIO_GAME_8_BIT_FLYBY = '#$game_fly$#';
    const S_AUDIO_GAME_8_BIT_MACHINE_GUN = '#$game_gun$#';
    const S_AUDIO_GAME_8_BIT_PHONE = '#$games_phone$#';
    const S_AUDIO_GAME_POWERUP = '#$games_powerup$#';

    const S_AUDIO_NATURE_WIND = '#$nature_wind$#';
    const S_AUDIO_NATURE_THUNDER = '#$nature_thunder$#';
    const S_AUDIO_NATURE_JUNGLE = '#$nature_jungle$#';
    const S_AUDIO_NATURE_RAIN = '#$nature_rain$#';
    const S_AUDIO_NATURE_FOREST = '#$nature_forest$#';
    const S_AUDIO_NATURE_SEA = '#$nature_sea$#';
    const S_AUDIO_NATURE_FIRE = '#$nature_fire$#';
    const S_AUDIO_NATURE_STREAM = '#$nature_stream$#';

    const S_AUDIO_THINGS = '#$thing_all$#';
    const S_AUDIO_ANIMALS_ = '#$animals_all$#';
    const S_AUDIO_HUMAN = '#$human_all$#';
    const S_AUDIO_MUSIC = '#$music_all$#';

    /**
     * Поиск и отображение звуков
     *
     * @param $text
     * @param $isShowSound
     * @param $sounds
     *
     * @return string
     */
    private function replaceSounds($text, $isShowSound, $sounds)
    {
        $isDelEffect = false;
        foreach ($sounds as $sound) {
            if ($isShowSound) {
                if (!isset($sound['sounds'])) {
                    $customParam['sounds'] = $sound[1];
                }
                if (!is_array($sound['sounds'])) {
                    $sound['sounds'] = [$sound['sounds'],];
                }

                $text = str_replace(
                    $sound['key'] ?? $sound[0],
                    $sound['sounds'][rand(0, count($sound['sounds']) - 1)],
                    $text);
            } else {
                $text = str_replace($sound['key'] ?? $sound[0],
                    '',
                    $text);
                if ($isDelEffect === false) {
                    $text = $this->getSEffect($text);
                    $isDelEffect = true;
                }
            }
        }
        return $text;
    }

    /**
     * Обработка кастомного массива звуков
     *
     * @param $text
     * @param $isShowSound
     * @param null $customParams
     *
     * @return string|null
     */
    protected function getCustomParamSound($text, $isShowSound, $customParams = null)
    {
        if (is_array($customParams)) {
            return $this->replaceSounds($text, $isShowSound, $customParams);
        }
        return null;
    }

    /**
     * Обработка стандартного массива звуков.
     *
     * @param $text
     * @param $isShowSound
     *
     * @return string
     */
    protected function getStandardParamSound($text, $isShowSound)
    {
        return $this->replaceSounds($text, $isShowSound, $this->standardParam);
    }

    /**
     * Получить Массив звуков
     *
     * @param $text
     * @param $isShowSound
     * @param null $customParams
     *
     * @return string|null
     */
    public function getSound($text, $isShowSound, $customParams = null)
    {
        if ($customParams) {
            $res = $this->getCustomParamSound($text, $isShowSound, $customParams);
            if ($res) {
                $text = $res;
            } else {
                $text = $this->getStandardParamSound($text, $isShowSound);
            }
        } else {
            $text = $this->getStandardParamSound($text, $isShowSound);
        }

        return $text;
    }

    /**
     * Удалить из текста все наложения на голос
     *
     * @param $text
     *
     * @return string
     */
    protected function getSEffect($text)
    {
        return str_replace([
            self::S_EFFECT_BEHIND_THE_WALL,
            self::S_EFFECT_HAMSTER,
            self::S_EFFECT_MEGAPHONE,
            self::S_EFFECT_PITCH_DOWN,
            self::S_EFFECT_PSYCHODELIC,
            self::S_EFFECT_PULSE,
            self::S_EFFECT_TRAIN_ANNOUNCE,
            self::S_EFFECT_END,
        ], '', $text);
    }
}