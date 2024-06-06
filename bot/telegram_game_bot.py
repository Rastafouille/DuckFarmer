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


# Fonction de démarrage pour la commande /start
async def start(update: Update, context: ContextTypes.DEFAULT_TYPE) -> None:
    user_id = update.message.from_user.id
    referral_link = f"https://t.me/DuckFarmerBot/DuckFarmerApp?startapp={user_id}"
    invite_message = "\n Hey! Join DuckFarmer play to Airdrop App with my referral link:\n\n🚀🐤 "+ referral_link+" 🧑‍🌾🚀"
    keyboard = [
        [
            InlineKeyboardButton("Play", url=f"https://t.me/DuckFarmerBot/DuckFarmerApp"),
            InlineKeyboardButton("Join Community", url="t.me/duckfarmerchannel"),
        ],
        [
            InlineKeyboardButton("Website", url="duckfarmer.xyz"),
            InlineKeyboardButton("Page X", url="https://x.com/DuckFarmerCoin")
        ],
           [InlineKeyboardButton("Forward to Friends", switch_inline_query=invite_message)]
    ]

    reply_markup = InlineKeyboardMarkup(keyboard)

    await update.message.reply_text(
            'Welcome! Use the buttons below :',
            reply_markup=reply_markup
        )

# Fonction de démarrage pour la commande /help
async def help(update: Update, context: ContextTypes.DEFAULT_TYPE) -> None:
    user_id = update.message.from_user.id
    referral_link = f"https://t.me/DuckFarmerBot/DuckFarmerApp?startapp={user_id}"
    invite_message = "\n Hey! Join DuckFarmer play to Airdrop App with my referral link:\n\n🚀🐤 "+ referral_link+" 🧑‍🌾🚀"


    keyboard = [
        [
            InlineKeyboardButton("Play", url=f"https://t.me/DuckFarmerBot/DuckFarmerApp"),
            InlineKeyboardButton("Join Community", url="t.me/duckfarmerchannel"),
        ],
        [
            InlineKeyboardButton("Website", url="duckfarmer.xyz"),
            InlineKeyboardButton("Page X", url="https://x.com/DuckFarmerCoin")
        ],
           [InlineKeyboardButton("Forward to Friends", switch_inline_query=invite_message)]
    ]

    reply_markup = InlineKeyboardMarkup(keyboard)

    await update.message.reply_text(
        'Hy! Use the buttons below :',
        reply_markup=reply_markup
    )

def main() -> None:
    # Initialiser l'application avec le jeton API
    application = Application.builder().token(TELEGRAM_BOT_API_TOKEN).build()

    application.add_handler(CommandHandler("start", start))
    application.add_handler(CommandHandler("help", help))

    # Démarrer le bot
    application.run_polling()

if __name__ == '__main__':
    main()
