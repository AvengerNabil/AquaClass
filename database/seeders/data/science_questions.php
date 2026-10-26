<?php

return [
    // -------------------------------------------------------------
    // 1. Grade 1 Science: Living vs Non-Living (25 questions)
    // -------------------------------------------------------------
    'g1_living' => [
        'activity' => [
            'title' => 'Tidepool Wonders: Living vs Non-Living',
            'type' => 'multiple_choice',
            'difficulty' => 'easy',
            'instructions' => 'Explore the seashore and discover what makes something living or non-living!',
            'max_points' => 100,
        ],
        'questions' => [
            [
                'question' => 'Which of these seashore items is a LIVING thing?',
                'correct' => 'A clownfish',
                'options' => ['A smooth pebble', 'A clownfish', 'A plastic bucket', 'A grain of sand'],
                'explanation' => 'Clownfish breathe, eat food, grow, and reproduce — they are alive!',
            ],
            [
                'question' => 'Which of these ocean items is a NON-LIVING thing?',
                'correct' => 'A sea shell on the beach (empty)',
                'options' => ['A sea turtle', 'A kelp plant', 'A sea shell on the beach (empty)', 'A swimming seal'],
                'explanation' => 'An empty seashell cannot grow, eat, or breathe — it is non-living.',
            ],
            [
                'question' => 'What do all living animals need to survive?',
                'correct' => 'Food, water, and oxygen',
                'options' => ['Food, water, and oxygen', 'Toys and video games', 'Sunlight only', 'Rocks and sand'],
                'explanation' => 'Living things need nourishment, hydration, and oxygen to stay alive.',
            ],
            [
                'question' => 'Do plants (like seaweed and kelp) count as living things?',
                'correct' => 'Yes, plants grow and need sunlight and water',
                'options' => ['Yes, plants grow and need sunlight and water', 'No, only animals are alive', 'No, because they cannot walk', 'Only when underwater'],
                'explanation' => 'Plants are living organisms that absorb nutrients, produce energy, and grow!',
            ],
            [
                'question' => 'Is sea water itself a living thing?',
                'correct' => 'No, water is non-living',
                'options' => ['Yes, because it moves with waves', 'No, water is non-living', 'Yes, it breathes', 'Only when warm'],
                'explanation' => 'Water moves, but it does not grow, eat, or reproduce. It is non-living.',
            ],
            [
                'question' => 'What happens to living baby sea turtles as time passes?',
                'correct' => 'They grow bigger and stronger',
                'options' => ['They shrink', 'They grow bigger and stronger', 'They turn into rocks', 'They never change'],
                'explanation' => 'All living creatures grow and develop over time.',
            ],
            [
                'question' => 'Can a beach toy boat grow bigger if you water it?',
                'correct' => 'No, non-living things do not grow',
                'options' => ['Yes, in 3 days', 'No, non-living things do not grow', 'Yes, with fertilizer', 'Only in salt water'],
                'explanation' => 'Toys are non-living objects; they do not have cells or metabolism.',
            ],
            [
                'question' => 'How can you tell a hermit crab is alive?',
                'correct' => 'It moves, eats, and responds to touch',
                'options' => ['It is made of metal', 'It stays still forever', 'It moves, eats, and responds to touch', 'It is made of glass'],
                'explanation' => 'Living creatures react to their environment and move around.',
            ],
            [
                'question' => 'Which sense does a seal use to hear splashes in the water?',
                'correct' => 'Hearing (ears)',
                'options' => ['Sight (eyes)', 'Hearing (ears)', 'Smell (nose)', 'Touch (whiskers)'],
                'explanation' => 'Seals have sensitive hearing adapted for both air and water.',
            ],
            [
                'question' => 'Which body part do seals use to feel vibrations in murky water?',
                'correct' => 'Their whiskers',
                'options' => ['Their tail', 'Their teeth', 'Their whiskers', 'Their claws'],
                'explanation' => 'A seal\'s whiskers can detect tiny water movements made by swimming fish!',
            ],
            [
                'question' => 'Where do baby sea turtles hatch from?',
                'correct' => 'Eggs buried in warm beach sand',
                'options' => ['Eggs buried in warm beach sand', 'Caves under the reef', 'Treetops', 'Snow banks'],
                'explanation' => 'Mother turtles lay soft eggs in sandy nests on the beach.',
            ],
            [
                'question' => 'Which of these can reproduce (have babies)?',
                'correct' => 'A sea otter',
                'options' => ['A surfboard', 'A sea otter', 'A sand dune', 'A seashell'],
                'explanation' => 'Living organisms reproduce to create the next generation.',
            ],
            [
                'question' => 'Is sunlight living or non-living?',
                'correct' => 'Non-living energy from the sun',
                'options' => ['Living organism', 'Non-living energy from the sun', 'An ocean mammal', 'A plant'],
                'explanation' => 'Sunlight is radiant energy from space, non-living.',
            ],
            [
                'question' => 'Which habitat is the natural home of a polar bear?',
                'correct' => 'Cold Arctic ice and ocean waters',
                'options' => ['Warm tropical coral reef', 'Cold Arctic ice and ocean waters', 'Dry sandy desert', 'Deep rainforest'],
                'explanation' => 'Polar bears thrive in the icy Arctic biome.',
            ],
            [
                'question' => 'What do sea gulls eat to give their bodies energy?',
                'correct' => 'Fish, crabs, and insects',
                'options' => ['Rocks and plastic', 'Fish, crabs, and insects', 'Sand and seawater', 'Wood and metal'],
                'explanation' => 'Living birds need food for energy and health.',
            ],
            [
                'question' => 'Which of these is a living organism found clinging to rocky cliffs?',
                'correct' => 'Barnacles',
                'options' => ['Barnacles', 'Sand grains', 'Pebbles', 'Plastic wrappers'],
                'explanation' => 'Barnacles are small crustacean animals that cement themselves to rocks.',
            ],
            [
                'question' => 'Do living things need rest or sleep?',
                'correct' => 'Yes, living creatures need rest to recharge their bodies',
                'options' => ['No, they swim 24 hours non-stop forever', 'Yes, living creatures need rest to recharge their bodies', 'Only robots need sleep', 'Never'],
                'explanation' => 'Rest is essential for living animals to repair cells and conserve energy.',
            ],
            [
                'question' => 'Which of these items would decompose (break down naturally) because it was once alive?',
                'correct' => 'A piece of drift wood or dried seaweed',
                'options' => ['A glass bottle', 'A piece of drift wood or dried seaweed', 'A aluminum soda can', 'A rubber tire'],
                'explanation' => 'Organic matter from trees and seaweed decomposes back into nature.',
            ],
            [
                'question' => 'What part of a seaweed plant anchors it firmly to the seabed rock?',
                'correct' => 'Holdfast',
                'options' => ['Holdfast', 'Flower', 'Bark', 'Twig'],
                'explanation' => 'Seaweed uses a root-like structure called a holdfast to grip rocks!',
            ],
            [
                'question' => 'Is coral a rock, a plant, or an animal?',
                'correct' => 'An animal (tiny coral polyps working together)',
                'options' => ['A rock', 'A plant', 'An animal (tiny coral polyps working together)', 'A fungus'],
                'explanation' => 'Corals are colonies of tiny animals called polyps with hard calcium skeletons!',
            ],
            [
                'question' => 'Why does a baby fish need oxygen?',
                'correct' => 'To breathe and fuel its cells',
                'options' => ['To sink to the bottom', 'To breathe and fuel its cells', 'To turn purple', 'To change into a rock'],
                'explanation' => 'Oxygen is essential for cellular respiration in living animals.',
            ],
            [
                'question' => 'Which of the following is human-made and non-living?',
                'correct' => 'A lighthouse',
                'options' => ['A lighthouse', 'A blue whale', 'A coral reef', 'A mangrove tree'],
                'explanation' => 'A lighthouse is built from stone, glass, and metal by humans.',
            ],
            [
                'question' => 'What happens to a plant if it gets no water or sunlight?',
                'correct' => 'It wilts and dies because it needs them to live',
                'options' => ['It grows faster', 'It turns into a fish', 'It wilts and dies because it needs them to live', 'Nothing at all'],
                'explanation' => 'Living plants depend on light and water for photosynthesis.',
            ],
            [
                'question' => 'Which animal breathes air at the ocean surface with a blowhole?',
                'correct' => 'A whale',
                'options' => ['A shark', 'A whale', 'A starfish', 'A clam'],
                'explanation' => 'Whales are mammals that must surface to breathe atmospheric air.',
            ],
            [
                'question' => 'Are clouds in the sky alive?',
                'correct' => 'No, clouds are made of non-living water droplets',
                'options' => ['Yes, because they float', 'No, clouds are made of non-living water droplets', 'Yes, they eat rain', 'Yes, they are giant white birds'],
                'explanation' => 'Clouds are condensed water vapor in the atmosphere: non-living.',
            ],
        ],
    ],

    // -------------------------------------------------------------
    // 2. Grade 2 Science: Ocean Habitats & Adaptations (25 questions)
    // -------------------------------------------------------------
    'g2_habitats' => [
        'activity' => [
            'title' => 'Ocean Explorers: Gills, Fins & Habitats',
            'type' => 'multiple_choice',
            'difficulty' => 'easy',
            'instructions' => 'Discover how marine animals adapt, breathe, camouflage, and survive in oceanic zones!',
            'max_points' => 100,
        ],
        'questions' => [
            [
                'question' => 'What special organ do fish use to breathe dissolved oxygen underwater?',
                'correct' => 'Gills',
                'options' => ['Lungs', 'Gills', 'Nose', 'Blowhole'],
                'explanation' => 'Gills absorb oxygen directly from the water flowing over them.',
            ],
            [
                'question' => 'Why do clownfish hide safely inside sea anemones?',
                'correct' => 'The anemone protects them from predators with stinging tentacles',
                'options' => ['The anemone protects them from predators with stinging tentacles', 'To stay warm in winter', 'Because they cannot swim', 'To eat the anemone\'s rocks'],
                'explanation' => 'Clownfish have a mucus coating protecting them from anemone stings in a mutual symbiosis!',
            ],
            [
                'question' => 'What do sea turtles use their broad front flippers for?',
                'correct' => 'Propelling through water like wings',
                'options' => ['Walking on treetops', 'Propelling through water like wings', 'Catching flying birds', 'Digging burrows in trees'],
                'explanation' => 'Sea turtle flippers work like graceful wings underwater.',
            ],
            [
                'question' => 'What does a flounder fish do to hide from sharks?',
                'correct' => 'Lies flat and matches the sandy seafloor colors',
                'options' => ['Flies into the clouds', 'Lies flat and matches the sandy seafloor colors', 'Barks loudly', 'Turns into a lobster'],
                'explanation' => 'Flounders have camouflage that perfectly mimics ocean floor textures.',
            ],
            [
                'question' => 'What is the deep, dark ocean layer where sunlight cannot reach called?',
                'correct' => 'The Midnight Zone (Aphotic Zone)',
                'options' => ['The Sunlight Zone', 'The Midnight Zone (Aphotic Zone)', 'The Beach Pool', 'The Splash Zone'],
                'explanation' => 'Below 1,000 meters, complete darkness reigns in the midnight zone.',
            ],
            [
                'question' => 'How do deep-sea anglerfish lure prey in pitch darkness?',
                'correct' => 'With a glowing bioluminescent light on their head',
                'options' => ['With a flashlight battery', 'With a glowing bioluminescent light on their head', 'By singing songs', 'By making loud drum beats'],
                'explanation' => 'Bioluminescent bacteria in their lure attract curious prey in the dark!',
            ],
            [
                'question' => 'Why do penguins have a thick layer of blubber (fat)?',
                'correct' => 'To keep their bodies warm in freezing Antarctic waters',
                'options' => ['To help them sink like rocks', 'To keep their bodies warm in freezing Antarctic waters', 'To change color in summer', 'To fly faster'],
                'explanation' => 'Blubber provides crucial thermal insulation against freezing cold.',
            ],
            [
                'question' => 'What defense mechanism does an octopus use when startled by a predator?',
                'correct' => 'Shoots a cloud of dark ink and jet-propels away',
                'options' => ['Plays guitar', 'Shoots a cloud of dark ink and jet-propels away', 'Freezes into an ice cube', 'Climbs a palm tree'],
                'explanation' => 'The ink screen blinds and confuses the predator while the octopus escapes!',
            ],
            [
                'question' => 'Which marine mammal holds hands with its partner so they don\'t drift apart while sleeping?',
                'correct' => 'Sea otters',
                'options' => ['Sea otters', 'Hammerhead sharks', 'Moray eels', 'Blue whales'],
                'explanation' => 'Sea otters wrap in giant kelp and hold paws so currents don\'t separate them!',
            ],
            [
                'question' => 'What is a coral reef built from?',
                'correct' => 'Calcium carbonate skeletons produced by tiny polyps',
                'options' => ['Melted plastic from ships', 'Calcium carbonate skeletons produced by tiny polyps', 'Mud baked in the sun', 'Tree trunks from islands'],
                'explanation' => 'Generations of tiny polyps build limestone reef architectures.',
            ],
            [
                'question' => 'Why do sharks have multiple rows of sharp teeth that continually regrow?',
                'correct' => 'To easily replace teeth lost while catching prey',
                'options' => ['To show off at parties', 'To easily replace teeth lost while catching prey', 'To chew seaweed', 'Because they have no bones'],
                'explanation' => 'Sharks lose thousands of teeth over a lifetime; new ones slide forward into place.',
            ],
            [
                'question' => 'What is the skeleton of a shark made of?',
                'correct' => 'Flexible cartilage (like our human ears and nose)',
                'options' => ['Heavy iron', 'Flexible cartilage (like our human ears and nose)', 'Hard granite rock', 'Wood fibers'],
                'explanation' => 'Cartilage makes sharks light, agile, and flexible swimmers!',
            ],
            [
                'question' => 'Which sea animal can change both its skin color AND texture to match rocks?',
                'correct' => 'Cuttlefish and octopuses',
                'options' => ['Jellyfish', 'Cuttlefish and octopuses', 'Dolphins', 'Seagulls'],
                'explanation' => 'Chromatophores and papillae allow cephalopods to mimic coral and rocks instantly!',
            ],
            [
                'question' => 'What purpose does a dolphin\'s blowhole serve?',
                'correct' => 'Breathing air at the water surface',
                'options' => ['Eating small shrimp', 'Breathing air at the water surface', 'Spraying water for fun only', 'Hearing echoes'],
                'explanation' => 'The blowhole is a nostril located on top of the dolphin\'s head for quick breathing.',
            ],
            [
                'question' => 'What do dolphins use "echolocation" for?',
                'correct' => 'Navigating and locating food using sound wave bounces',
                'options' => ['Talking to sailors', 'Navigating and locating food using sound wave bounces', 'Cooking fish', 'Sleeping deeply'],
                'explanation' => 'Sound clicks bounce off objects, giving dolphins an acoustic map of their world.',
            ],
            [
                'question' => 'How do hermit crabs protect their soft back bodies?',
                'correct' => 'They move into abandoned snail shells',
                'options' => ['They weave seaweed blankets', 'They move into abandoned snail shells', 'They hide in caves forever', 'They grow metal shells'],
                'explanation' => 'As hermit crabs grow, they search for larger spiral snail shells to occupy.',
            ],
            [
                'question' => 'Why do flying fish jump out of the water and glide through the air?',
                'correct' => 'To escape fast-swimming predators like tuna and dolphins',
                'options' => ['To catch flying birds', 'To sunbathe in the wind', 'To escape fast-swimming predators like tuna and dolphins', 'Because they forgot how to swim'],
                'explanation' => 'Gliding up to hundreds of meters in the air allows flying fish to escape danger.',
            ],
            [
                'question' => 'What gives flamingos their pink color from their ocean and lagoon diet?',
                'correct' => 'Carotenoid pigments in the shrimp and algae they eat',
                'options' => ['Pink saltwater dye', 'Carotenoid pigments in the shrimp and algae they eat', 'Sunburn on their feathers', 'Drinking pink berry juice'],
                'explanation' => 'Their natural diet of brine shrimp and red algae contains carotenoids.',
            ],
            [
                'question' => 'What is a "tide pool"?',
                'correct' => 'Pockets of seawater trapped in rocky hollows when the tide goes out',
                'options' => ['A swimming pool built for cruise ships', 'Pockets of seawater trapped in rocky hollows when the tide goes out', 'A giant waterfall in the ocean', 'An indoor fish tank'],
                'explanation' => 'Tide pools provide temporary habitats for starfish, anemones, and crabs at low tide.',
            ],
            [
                'question' => 'How do mussels stay attached to rocks despite crashing waves?',
                'correct' => 'With strong, sticky fibers called byssal threads',
                'options' => ['With superglue from bottles', 'With strong, sticky fibers called byssal threads', 'With magnets', 'By holding their breath'],
                'explanation' => 'Byssal threads ("beards") anchor mussels securely against huge ocean swells.',
            ],
            [
                'question' => 'What kind of animal is a jellyfish?',
                'correct' => 'An invertebrate with no brain, heart, or bones',
                'options' => ['A scaly fish', 'An invertebrate with no brain, heart, or bones', 'An ocean bird', 'A marine mammal'],
                'explanation' => 'Jellyfish are cnidarians made of 95% water with simple nerve nets.',
            ],
            [
                'question' => 'Why do kelp forests float upward toward the sunny surface?',
                'correct' => 'They have small gas-filled air bladders (pneumatocysts) on their blades',
                'options' => ['Helium balloons', 'They have small gas-filled air bladders (pneumatocysts) on their blades', 'Fish carry them up', 'They are lighter than air'],
                'explanation' => 'Air bladders keep the kelp fronds buoyed toward sunlight for photosynthesis.',
            ],
            [
                'question' => 'Which animal is known as the "gardener of the coral reef" for eating excess algae?',
                'correct' => 'Parrotfish',
                'options' => ['Parrotfish', 'Great white shark', 'Squid', 'Swordfish'],
                'explanation' => 'Parrotfish scrape algae off coral rocks and produce white sand in their digestion!',
            ],
            [
                'question' => 'What is bioluminescence?',
                'correct' => 'Light produced by chemical reactions inside living organisms',
                'options' => ['Sunlight reflecting on mirrors', 'Light produced by chemical reactions inside living organisms', 'Electric wires under the sea', 'Phosphorus paint from shipwrecks'],
                'explanation' => 'Luciferin reactions allow deep-sea animals to glow and communicate.',
            ],
            [
                'question' => 'Why is clean ocean water so vital for coral reefs?',
                'correct' => 'Corals need clear sunlight to power their symbiotic algae (zooxanthellae)',
                'options' => ['So tourists can take pictures', 'Corals need clear sunlight to power their symbiotic algae (zooxanthellae)', 'Corals drink freshwater', 'So sharks can sleep'],
                'explanation' => 'Muddy or polluted water blocks the sunlight that reef algae need to produce food.',
            ],
        ],
    ],

    // -------------------------------------------------------------
    // 3. Grade 3 Science: The Water Cycle & Weather (25 questions)
    // -------------------------------------------------------------
    'g3_watercycle' => [
        'activity' => [
            'title' => 'Cloud to Ocean: Water Cycle & Weather',
            'type' => 'multiple_choice',
            'difficulty' => 'medium',
            'instructions' => 'Follow water drops as they evaporate, condense, precipitate, and flow back to the sea!',
            'max_points' => 100,
        ],
        'questions' => [
            [
                'question' => 'What is the process called when liquid ocean water turns into invisible water vapor gas?',
                'correct' => 'Evaporation',
                'options' => ['Evaporation', 'Condensation', 'Precipitation', 'Freezing'],
                'explanation' => 'Heat from the sun warms ocean surface water, causing it to evaporate into vapor.',
            ],
            [
                'question' => 'What process happens when water vapor cools down high in the air to form clouds?',
                'correct' => 'Condensation',
                'options' => ['Evaporation', 'Condensation', 'Melting', 'Collection'],
                'explanation' => 'Cool air causes water vapor to condense into billions of tiny cloud droplets.',
            ],
            [
                'question' => 'When clouds get too heavy with droplets, water falls back to Earth. What is this called?',
                'correct' => 'Precipitation',
                'options' => ['Precipitation', 'Evaporation', 'Transpiration', 'Sublimation'],
                'explanation' => 'Rain, snow, sleet, and hail are all forms of precipitation.',
            ],
            [
                'question' => 'What powers the entire Earth water cycle and provides the heat energy?',
                'correct' => 'The Sun',
                'options' => ['The Moon', 'The Sun', 'Ocean currents', 'Windmills'],
                'explanation' => 'The sun\'s radiant solar energy drives the global water cycle.',
            ],
            [
                'question' => 'When rain falls on mountains and flows into rivers back toward the ocean, what is this called?',
                'correct' => 'Runoff or collection',
                'options' => ['Runoff or collection', 'Condensation', 'Photosynthesis', 'Respiration'],
                'explanation' => 'Surface runoff drains rainwater back into lakes, rivers, and the ocean.',
            ],
            [
                'question' => 'What instrument is used to measure air temperature?',
                'correct' => 'Thermometer',
                'options' => ['Barometer', 'Thermometer', 'Anemometer', 'Rain gauge'],
                'explanation' => 'A thermometer measures degrees in Celsius or Fahrenheit.',
            ],
            [
                'question' => 'What instrument is used to measure wind speed at a coastal weather station?',
                'correct' => 'Anemometer',
                'options' => ['Thermometer', 'Anemometer', 'Compass', 'Telescope'],
                'explanation' => 'An anemometer has spinning cups that measure wind speed.',
            ],
            [
                'question' => 'What instrument measures the amount of rainfall collected over 24 hours?',
                'correct' => 'Rain gauge',
                'options' => ['Rain gauge', 'Speedometer', 'Scale', 'Microscope'],
                'explanation' => 'A rain gauge collects and measures millimeters of precipitation.',
            ],
            [
                'question' => 'What are the three main states of water on Earth?',
                'correct' => 'Solid (ice), Liquid (water), and Gas (water vapor)',
                'options' => ['Solid (ice), Liquid (water), and Gas (water vapor)', 'Cold, Warm, and Hot', 'Cloud, Rain, and Mud', 'Ocean, Lake, and Pond'],
                'explanation' => 'Water exists as solid ice, liquid water, and gaseous steam/vapor.',
            ],
            [
                'question' => 'At what temperature Celsius does liquid water freeze into solid ice?',
                'correct' => '0°C',
                'options' => ['0°C', '10°C', '32°C', '100°C'],
                'explanation' => 'Water freezes at 0°C (32°F).',
            ],
            [
                'question' => 'At what temperature Celsius does liquid water boil into steam at sea level?',
                'correct' => '100°C',
                'options' => ['50°C', '75°C', '100°C', '200°C'],
                'explanation' => 'Water boils at 100°C (212°F).',
            ],
            [
                'question' => 'What percentage of all water on planet Earth is stored in the oceans?',
                'correct' => 'About 97%',
                'options' => ['25%', '50%', '75%', 'About 97%'],
                'explanation' => 'The vast majority of Earth\'s water is saline ocean water (~97%).',
            ],
            [
                'question' => 'Where is most of Earth\'s fresh (non-salty) water stored?',
                'correct' => 'Locked in polar ice caps and glaciers',
                'options' => ['In swimming pools', 'Locked in polar ice caps and glaciers', 'In desert sand', 'In clouds only'],
                'explanation' => 'Glaciers and polar ice sheets hold over two-thirds of all fresh water on Earth.',
            ],
            [
                'question' => 'What is the release of water vapor into the air from plant leaves called?',
                'correct' => 'Transpiration',
                'options' => ['Transpiration', 'Perspiration', 'Sedimentation', 'Filtration'],
                'explanation' => 'Plants transpire moisture through tiny leaf pores called stomata.',
            ],
            [
                'question' => 'Why does ocean water taste salty while raindrops taste fresh?',
                'correct' => 'When ocean water evaporates, the salt remains behind in the sea',
                'options' => ['Rainclouds filter salt with giant screens', 'When ocean water evaporates, the salt remains behind in the sea', 'Clouds add sugar to the rain', 'Fish drink all the salt before it rains'],
                'explanation' => 'Only pure H2O evaporates into the air; heavy dissolved salts stay in the sea.',
            ],
            [
                'question' => 'What causes the daily rise and fall of ocean tides along coastlines?',
                'correct' => 'The gravitational pull of the Moon and the Sun',
                'options' => ['Strong ocean winds', 'The gravitational pull of the Moon and the Sun', 'Whales splashing their tails', 'Earthquakes under the sand'],
                'explanation' => 'The moon\'s gravitational pull tugs on Earth\'s oceans, creating high and low tides.',
            ],
            [
                'question' => 'What are big, puffy, white clouds that look like cotton balls on sunny days called?',
                'correct' => 'Cumulus clouds',
                'options' => ['Cumulus clouds', 'Stratus clouds', 'Cirrus clouds', 'Fog'],
                'explanation' => 'Cumulus clouds are fluffy and indicate fair weather.',
            ],
            [
                'question' => 'What are thin, feathery clouds high up in the sky made of ice crystals called?',
                'correct' => 'Cirrus clouds',
                'options' => ['Cirrus clouds', 'Cumulonimbus clouds', 'Storm clouds', 'Low fog'],
                'explanation' => 'Cirrus clouds sit high in the troposphere where temperatures are below freezing.',
            ],
            [
                'question' => 'What are towering, dark, anvil-shaped storm clouds that bring thunder and lightning called?',
                'correct' => 'Cumulonimbus clouds',
                'options' => ['Cumulonimbus clouds', 'Fog', 'Cirrocumulus', 'Dew'],
                'explanation' => 'Cumulonimbus clouds are giant thunderstorm clouds.',
            ],
            [
                'question' => 'What is fog?',
                'correct' => 'A cloud that touches or forms close to the ground',
                'options' => ['Smoke from underwater volcanoes', 'A cloud that touches or forms close to the ground', 'A type of rain that cannot fall', 'Dry dust blowing from islands'],
                'explanation' => 'Fog is simply a low-lying cloud formed when air near the ground cools.',
            ],
            [
                'question' => 'How does a rainbow form across the seaside sky after rain?',
                'correct' => 'Sunlight is refracted and reflected through suspended raindrops',
                'options' => ['Paint from ships evaporates', 'Sunlight is refracted and reflected through suspended raindrops', 'Warm wind mixes ocean colors', 'Clouds glow in the dark'],
                'explanation' => 'Raindrops act like tiny prisms, splitting white sunlight into its rainbow spectrum.',
            ],
            [
                'question' => 'What creates wind on our planet?',
                'correct' => 'Uneven heating of Earth\'s surface creating differences in air pressure',
                'options' => ['Trees waving their branches', 'Uneven heating of Earth\'s surface creating differences in air pressure', 'Fish swimming fast', 'Ocean waves pushing air'],
                'explanation' => 'Warm air rises and cooler, dense air rushes in to take its place, creating wind.',
            ],
            [
                'question' => 'What is groundwater?',
                'correct' => 'Water that soaks down into soil and rock layers beneath Earth\'s surface',
                'options' => ['Water on the kitchen floor', 'Water that soaks down into soil and rock layers beneath Earth\'s surface', 'Puddles on the beach', 'Snow on rooftops'],
                'explanation' => 'Groundwater fills underground aquifers and feeds wells and springs.',
            ],
            [
                'question' => 'Does the total amount of water on Earth ever really change?',
                'correct' => 'No, the same water has been recycled for billions of years',
                'options' => ['Yes, half disappears every century', 'No, the same water has been recycled for billions of years', 'Yes, water comes from comets every day', 'Yes, the oceans grow by 50% each year'],
                'explanation' => 'Earth\'s water is in a closed system; the water you drink was once drunk by dinosaurs!',
            ],
            [
                'question' => 'Why are oceans important for regulating Earth\'s global climate?',
                'correct' => 'Oceans absorb massive amounts of solar heat and distribute it through currents',
                'options' => ['They make ice cubes for polar bears', 'Oceans absorb massive amounts of solar heat and distribute it through currents', 'They prevent the sky from falling', 'They make the continents float'],
                'explanation' => 'Oceans act as Earth\'s climate engine, moderating planetary temperatures.',
            ],
        ],
    ],
];
