# Flip-card

Mimics the look of those classic Solari-style boards. 

## Installation

Clone the repo and place the JS and CSS files in your directories. Link them into the required files, and go nuts. :)

## Usage
Usage is simple as a reusable custom tag. Specifying the `type` of the message changes whether the card starts at 'A' (for text messages) or on '0' (for numerical messages, counters etc). 
```html
<flip-card type="text" theme="classic">Your text here</flip-card>
<flip-card type="number" theme="material">123456</flip-card>
```

## Styles
All styles come with fitting fonts. The available styles are:
- Classic (default)
- Material
- Retro
- Neon