import logging
from telegram import Update, InlineKeyboardButton, InlineKeyboardMarkup
from telegram.ext import Application, CommandHandler, ContextTypes
from config import TELEGRAM_BOT_API_TOKEN  # Importer le jeton depuis le fichier de configuration

# Configuration du journal
logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s', 
    level=logging.INFO
)
logger = logging.getLogger(__name__)

# Fonction pour gÃ©nÃ©rer le message d'invitation avec le lien de parrainage
def generate_invite_message(username):
    referral_link = f"https://t.me/DuckFarmerBot/DuckFarmerApp?startapp={username}"
    invite_message = "\n Hey! Join DuckFarmer play to Airdrop App with my referral link:\nðŸš€ðŸ¤ "+ referral_link+" ðŸ§‘â€ðŸŒ¾ðŸš€"
    return invite_message

# Fonction pour gÃ©rer les commandes /start et /help
async def start_or_help(update: Update, context: ContextTypes.DEFAULT_TYPE) -> None:
    user = update.message.from_user
    username = user.username if user.username else str(user.id)
    invite_message = generate_invite_message(username)

    keyboard = [
        [InlineKeyboardButton("Invite Friends", switch_inline_query=invite_message)],
        [
            InlineKeyboardButton("Website", url="duckfarmer.xyz"),
            InlineKeyboardButton("Join Community", url="t.me/duckfarmerchannel"),
            InlineKeyboardButton("Page X", url="https://x.com/DuckFarmerGame")
        ],
        [ InlineKeyboardButton("Play", url=f"https://t.me/DuckFarmerBot/DuckFarmerApp")]
    ]

    reply_markup = InlineKeyboardMarkup(keyboard)

    await update.message.reply_text(
        'Welcome! Use the buttons below :',
        reply_markup=reply_markup
    )

def main() -> None:
    # Initialiser l'application avec le jeton API
    application = Application.builder().token(TELEGRAM_BOT_API_TOKEN).build()

    application.add_handler(CommandHandler("start", start_or_help))
    application.add_handler(CommandHandler("help", start_or_help))

    # DÃ©marrer le bot
    application.run_polling()

if __name__ == '__main__':
    main()