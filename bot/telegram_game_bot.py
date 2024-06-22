import logging
from telegram import Update, InlineKeyboardButton, InlineKeyboardMarkup
from telegram.ext import Application, CommandHandler, ContextTypes, CallbackQueryHandler
import requests  # Pour faire des requêtes HTTP
from PIL import Image, ImageDraw, ImageFont
import os
import time
from config import TELEGRAM_BOT_API_TOKEN  # Importer le jeton depuis le fichier de configuration

# Configuration du journal
logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s', 
    level=logging.INFO
)
logger = logging.getLogger(__name__)

def draw_img(farmer_name,score, player_number, rank, p1, p2, p3):
    image_path = 'stats.png'
    #image_path = '/home/user/DuckFarmer/bot/stats.png'

    image = Image.open(image_path)

    # Load the font
    font_path = 'Minecraft.ttf'  # Assurez-vous que ce chemin est correct
    #font_path = '/home/user/DuckFarmer/bot/Minecraft.ttf'  

    font_size = 70  # Ajustez la taille de la police si nécessaire
    font = ImageFont.truetype(font_path, font_size)
    # Initialize ImageDraw
    draw = ImageDraw.Draw(image)

    # Function to draw text on the image
    def draw_text(content, position,text_color):
        draw.text(position, content, font=font, fill=text_color)  

    # Draw the text on the image
    draw_text(farmer_name, (100, 110),(255, 255, 255))
    draw_text(score, (420, 200),(255, 255, 255))
    draw_text(rank, (420, 275),(255, 255, 255))

    font_size = 40
    font = ImageFont.truetype(font_path, font_size)

    draw_text(p1, (160, 445),(255, 215, 0))
    draw_text(p2, (160, 505),(192, 192, 192))
    draw_text(p3, (160, 565),(205, 127, 50))

    font_size = 70
    font = ImageFont.truetype(font_path, font_size)
    draw_text(player_number, (300, 675),(255, 255, 255))
    divider = 3
    # Redimensionner l'image pour réduire la taille
    #image = image.resize((int(1024/divider), int(1413/divider)), Image.Resampling.LANCZOS)

    # Save the image
    output_path = 'stats2.png'
    image.save(output_path, optimize=True, quality=85)  # Compresser l'image

    return output_path


# Fonction pour générer le message d'invitation avec le lien de parrainage
def generate_invite_message(username):
    referral_link = f"https://t.me/DuckFarmerBot/DuckFarmerApp?startapp={username}"
    invite_message = "\n Hey! Join DuckFarmer play to Airdrop App with my referral link:\n🚀🦆 " + referral_link + " 👩‍🌾🚀"
    return invite_message

# Fonction pour gérer les commandes /start et /help
async def start_or_help(update: Update, context: ContextTypes.DEFAULT_TYPE) -> None:
    user = update.message.from_user
    username = user.username if user.username else str(user.id)
    telegram_id = user.id  # Utiliser l'ID de l'utilisateur comme telegram_id
    invite_message = generate_invite_message(username)

    keyboard = [
        [InlineKeyboardButton("Invite Friends", switch_inline_query=invite_message)],
        [
            InlineKeyboardButton("Website", url="duckfarmer.xyz"),
            InlineKeyboardButton("Join Community", url="t.me/duckfarmerchannel"),
            InlineKeyboardButton("Page X", url="https://x.com/DuckFarmerGame")
        ],
        [InlineKeyboardButton("Play", url=f"https://t.me/DuckFarmerBot/DuckFarmerApp")],
        [InlineKeyboardButton("Stats", callback_data=f"stats:{telegram_id}:{username}")]
    ]

    reply_markup = InlineKeyboardMarkup(keyboard)

    await update.message.reply_text(
        'Welcome! Use the buttons below :',
        reply_markup=reply_markup
    )

# Fonction pour gérer les clics sur les boutons InlineKeyboardButton
async def button(update: Update, context: ContextTypes.DEFAULT_TYPE) -> None:
    query = update.callback_query
    # Répondre à la CallbackQuery pour éviter les problèmes clients
    await query.answer()

    # Vérifier si le bouton cliqué est "Stats"
    if query.data.startswith("stats:"):
        telegram_id = query.data.split(":")[1]
        username = query.data.split(":")[2]
        try:
            # Notifier l'utilisateur que l'image est en cours de préparation
            await query.edit_message_text(text="Preparing your stats, please wait...")

            start_time = time.time()  # Début du chronométrage

            # Faire une requête HTTP à la page web pour obtenir les statistiques en incluant le telegram_id
            response = requests.get(f"https://orange-frog-982231.hostingersite.com/app/get_stats.php?telegramid={telegram_id}")
            if response.status_code == 200:
                stats = response.json()
                # Récupérer les statistiques
                total_count = stats['data']['total_count']
                top_players = stats['data']['top_players']
                user_rank = stats['data']['user_rank']
                score = str(stats['data']['score'])
                #score= {score1[0]}

                # Générer les textes pour l'image
                p1 = (f"{top_players[0]['TELEGRAMFirst']}" if top_players[0]['TELEGRAMFirst'] else "Anonymous ") + f" -  {top_players[0]['SCORE']}" if len(top_players) > 1 else "N/A"
                p2 = (f"{top_players[1]['TELEGRAMFirst']}" if top_players[1]['TELEGRAMFirst'] else "Anonymous ") + f" -  {top_players[1]['SCORE']}" if len(top_players) > 1 else "N/A"
                p3 = (f"{top_players[2]['TELEGRAMFirst']}" if top_players[2]['TELEGRAMFirst'] else "Anonymous ") + f" -  {top_players[2]['SCORE']}" if len(top_players) > 1 else "N/A"

                # Générer l'image avec les statistiques
                image_path = draw_img(username,f"{score}", f"{total_count}", f"{user_rank}", p1, p2, p3)

                generation_time = time.time() - start_time  # Temps de génération
                logger.info(f"Temps de génération de l'image : {generation_time} secondes")

                # Envoyer l'image en réponse de manière asynchrone
                with open(image_path, 'rb') as photo:
                    await context.bot.send_photo(chat_id=query.message.chat_id, photo=photo)
                    logger.info(f"Image envoyée à {username} (Telegram ID: {telegram_id})")

                # Supprimer le fichier image après l'envoi si vous ne voulez pas le conserver
                os.remove(image_path)
                logger.info(f"Fichier image supprimé: {image_path}")

            else:
                await query.edit_message_text(text="Error retrieving statistics.")

        except Exception as e:
            await query.edit_message_text(text=f"An error occurred: {str(e)}")
            logger.error(f"Une erreur s'est produite lors du traitement de la demande de stats: {e}")
    else:
        await query.edit_message_text(text=f"Selected option: {query.data}")

def main() -> None:
    # Initialiser l'application avec le jeton API
    application = Application.builder().token(TELEGRAM_BOT_API_TOKEN).build()

    application.add_handler(CommandHandler("start", start_or_help))
    application.add_handler(CallbackQueryHandler(button))
    application.add_handler(CommandHandler("help", start_or_help))

    # Démarrer le bot
    application.run_polling()

if __name__ == '__main__':
    main()
