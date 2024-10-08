const CONST = {
    REACTION: [27, 30, 48, 40, 42],
    EMOIJ:[
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10,
        11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
        21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
        31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
        41, 42, 43, 44, 45, 46, 47, 48, 49, 50
    ],
    ICON: {
        1:{
            name: 'Sad face',
            src: new URL('/src/modules/chat/emojis/emo_sad.gif', import.meta.url),
            code: ':(',
            altCode: ':(',
            showInList: true,
        },
        2:{
            name: 'Laughing face',
            src: new URL('/src/modules/chat/emojis/emo_more_smile.gif', import.meta.url),
            code: ':D',
            altCode: ':D',
            showInList: true,
        },
        3:{
            name: 'Smiling face with sunglasses',
            src: new URL('/src/modules/chat/emojis/emo_lucky.gif', import.meta.url),
            code: '8-)',
            altCode: '8-)',
            showInList: true,
        },
        4:{
            name: 'Hushed face',
            src: new URL('/src/modules/chat/emojis/emo_surprise.gif', import.meta.url),
            code: ':o',
            altCode: ':o',
            showInList: true,
        },
        5:{
            name: 'Winking face',
            src: new URL('/src/modules/chat/emojis/emo_wink.gif', import.meta.url),
            code: ';)',
            altCode: ';)',
            showInList: true,
        },
        6:{
            name: 'Crying face',
            src: new URL('/src/modules/chat/emojis/emo_tears.gif', import.meta.url),
            code: ';(',
            altCode: ';(',
            showInList: true,
        },
        7:{
            name: 'Face with cold sweat',
            src: new URL('/src/modules/chat/emojis/emo_sweat.gif', import.meta.url),
            code: '(sweat)',
            altCode: '(sweat)',
            showInList: true,
        },
        8:{
            name: 'Silent face',
            src: new URL('/src/modules/chat/emojis/emo_mumu.gif', import.meta.url),
            code: ':|',
            altCode: ':|',
            showInList: true,
        },
        9:{
            name: 'Kiss face',
            src: new URL('/src/modules/chat/emojis/emo_kiss.gif', import.meta.url),
            code: ':*',
            altCode: ':*',
            showInList: true,
        },
        10:{
            name: 'Tongue face',
            src: new URL('/src/modules/chat/emojis/emo_tongueout.gif', import.meta.url),
            code: ':p',
            altCode: ':p',
            showInList: true,
        },
        11:{
            name: 'Blushing face',
            src: new URL('/src/modules/chat/emojis/emo_blush.gif', import.meta.url),
            code: '(blush)',
            altCode: '(blush)',
            showInList: true,
        },
        12:{
            name: 'Frowning face',
            src: new URL('/src/modules/chat/emojis/emo_wonder.gif', import.meta.url),
            code: ':^)',
            altCode: ':^)',
            showInList: true,
        },
        13:{
            name: 'Sleeping face',
            src: new URL('/src/modules/chat/emojis/emo_snooze.gif', import.meta.url),
            code: '|-)',
            altCode: '|-)',
            showInList: true,
        },
        14:{
            name: 'Smiling face with hearts',
            src: new URL('/src/modules/chat/emojis/emo_love.gif', import.meta.url),
            code: '(inlove)',
            altCode: '(inlove)',
            showInList: true,
        },
        15:{
            name: 'Bragging face',
            src: new URL('/src/modules/chat/emojis/emo_grin.gif', import.meta.url),
            code: '[:-',
            altCode: '[:-',
            showInList: true,
        },
        16:{
            name: 'Speaking face',
            src: new URL('/src/modules/chat/emojis/emo_talk.gif', import.meta.url),
            code: '(talk)',
            altCode: '(talk)',
            showInList: true,
        },
        17:{
            name: 'Sleepy face',
            src: new URL('/src/modules/chat/emojis/emo_yawn.gif', import.meta.url),
            code: '(yawn)',
            altCode: '(yawn)',
            showInList: true,
        },
        18:{
            name: 'Vomiting face',
            src: new URL('/src/modules/chat/emojis/emo_puke.gif', import.meta.url),
            code: '(puke)',
            altCode: '(puke)',
            showInList: true,
        },
        19:{
            name: 'Hair flip face',
            src: new URL('/src/modules/chat/emojis/emo_ikemen.gif', import.meta.url),
            code: '(emo)',
            altCode: '(emo)',
            showInList: true,
        },
        20:{
            name: 'Glasses face',
            src: new URL('/src/modules/chat/emojis/emo_otaku.gif', import.meta.url),
            code: '8-|',
            altCode: '8-|',
            showInList: true,
        },
        21:{
            name: 'Grinning face',
            src: new URL('/src/modules/chat/emojis/emo_ninmari.gif', import.meta.url),
            code: ':#)',
            altCode: ':#)',
            showInList: true,
        },
        22:{
            name: 'Nodding face',
            src: new URL('/src/modules/chat/emojis/emo_nod.gif', import.meta.url),
            code: '(nod)',
            altCode: '(nod)',
            showInList: true,
        },
        23:{
            name: 'Shaking head face',
            src: new URL('/src/modules/chat/emojis/emo_shake.gif', import.meta.url),
            code: '(shake)',
            altCode: '(shake)',
            showInList: true,
        },
        24:{
            name: 'Grinning face with sweat',
            src: new URL('/src/modules/chat/emojis/emo_wry_smile.gif', import.meta.url),
            code: '(^^.)',
            altCode: '(^^.)',
            showInList: true,
        },
        25:{
            name: 'Sweating face',
            src: new URL('/src/modules/chat/emojis/emo_whew.gif', import.meta.url),
            code: '(whew)',
            altCode: '(whew)',
            showInList: true,
        },
        26:{
            name: 'Clapping hands',
            src: new URL('/src/modules/chat/emojis/emo_clap.gif', import.meta.url),
            code: '(clap)',
            altCode: '(clap)',
            showInList: true,
        },
        27:{
            name: 'Bowing Person',
            src: new URL('/src/modules/chat/emojis/emo_bow.gif', import.meta.url),
            code: '(bow)',
            altCode: '(bow)',
            showInList: true,
        },
        28:{
            name: 'Saluting face',
            src: new URL('/src/modules/chat/emojis/emo_roger.gif', import.meta.url),
            code: '(roger)',
            altCode: '(roger)',
            showInList: true,
        },
        29:{
            name: 'Flexing Person',
            src: new URL('/src/modules/chat/emojis/emo_muscle.gif', import.meta.url),
            code: '(flex)',
            altCode: '(flex)',
            showInList: true,
        },
        30:{
            name: 'Dancer',
            src: new URL('/src/modules/chat/emojis/emo_dance.gif', import.meta.url),
            code: '(dance)',
            altCode: '(dance)',
            showInList: true,
        },
        31:{
            name: 'Funny face',
            src: new URL('/src/modules/chat/emojis/emo_komanechi.gif', import.meta.url),
            code: '(:/)',
            altCode: '(:/)',
            showInList: true,
        },
        32:{
            name: 'Raised fist',
            src: new URL('/src/modules/chat/emojis/emo_gogo.gif', import.meta.url),
            code: '(gogo)',
            altCode: '(gogo)',
            showInList: true,
        },
        33:{
            name: 'Thinking face',
            src: new URL('/src/modules/chat/emojis/emo_think.gif', import.meta.url),
            code: '(think)',
            altCode: '(think)',
            showInList: true,
        },
        34:{
            name: 'Please',
            src: new URL('/src/modules/chat/emojis/emo_please.gif', import.meta.url),
            code: '(please)',
            altCode: '(please)',
            showInList: true,
        },
        35:{
            name: 'Hurrying face',
            src: new URL('/src/modules/chat/emojis/emo_quick.gif', import.meta.url),
            code: '(quick)',
            altCode: '(quick)',
            showInList: true,
        },
        36:{
            name: 'Angry face',
            src: new URL('/src/modules/chat/emojis/emo_anger.gif', import.meta.url),
            code: '(anger)',
            altCode: '(anger)',
            showInList: true,
        },
        37:{
            name: 'Smiling face with horns',
            src: new URL('/src/modules/chat/emojis/emo_devil.gif', import.meta.url),
            code: '(devil)',
            altCode: '(devil)',
            showInList: true,
        },
        38:{
            name: 'Lightbulb',
            src: new URL('/src/modules/chat/emojis/emo_lightbulb.gif', import.meta.url),
            code: '(lightbulb)',
            altCode: '(lightbulb)',
            showInList: true,
        },
        39:{
            name: 'Star',
            src: new URL('/src/modules/chat/emojis/emo_star.gif', import.meta.url),
            code: '(*)',
            altCode: '(*)',
            showInList: true,
        },
        40:{
            name: 'Trembling heart',
            src: new URL('/src/modules/chat/emojis/emo_heart.gif', import.meta.url),
            code: '(h)',
            altCode: '(h)',
            showInList: true,
        },
        41:{
            name: 'Blooming flower',
            src: new URL('/src/modules/chat/emojis/emo_flower.gif', import.meta.url),
            code: '(F)',
            altCode: '(F)',
            showInList: true,
        },
        42:{
            name: 'Firecracker',
            src: new URL('/src/modules/chat/emojis/emo_cracker.gif', import.meta.url),
            code: '(cracker)',
            altCode: '(cracker)',
            showInList: true,
        },
        43:{
            name: 'Food',
            src: new URL('/src/modules/chat/emojis/emo_eat.gif', import.meta.url),
            code: '(eat)',
            altCode: '(eat)',
            showInList: true,
        },
        44:{
            name: 'Cake',
            src: new URL('/src/modules/chat/emojis/emo_cake.gif', import.meta.url),
            code: '(^)',
            altCode: '(^)',
            showInList: true,
        },
        45:{
            name: 'Coffee',
            src: new URL('/src/modules/chat/emojis/emo_coffee.gif', import.meta.url),
            code: '(coffee)',
            altCode: '(coffee)',
            showInList: true,
        },
        46:{
            name: 'Beer',
            src: new URL('/src/modules/chat/emojis/emo_beer.gif', import.meta.url),
            code: '(beer)',
            altCode: '(beer)',
            showInList: true,
        },
        47:{
            name: 'Handshake',
            src: new URL('/src/modules/chat/emojis/emo_handshake.gif', import.meta.url),
            code: '(handshake)',
            altCode: '(handshake)',
            showInList: true,
        },
        48:{
            name: 'Thumbs up',
            src: new URL('/src/modules/chat/emojis/emo_yes.gif', import.meta.url),
            code: '(y)',
            altCode: '(y)',
            showInList: true,
        },
        49:{
            name: 'Thumbs up',
            src: new URL('/src/modules/chat/emojis/rolling-on-the-floor-laughing.gif', import.meta.url),
            code: '=))',
            altCode: '=))',
            showInList: false,
        },
        50:{
            name: 'Smiling face',
            src: new URL('/src/modules/chat/emojis/emo_smile.gif', import.meta.url),
            code: ':)',
            altCode: ':)',
            showInList: true,
        },
    }
}

export default CONST;