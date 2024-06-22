import pyautogui
import time

# Fonction pour déplacer la souris de gauche à droite
def move_mouse():
    while True:
        # Position initiale de la souris
        start_x, start_y = pyautogui.position()
        
        # Déplacement de la souris de 200 pixels vers la droite en 1 seconde
        pyautogui.moveTo(start_x + 100, start_y, duration=0.25)
        
        # Pause avant de revenir à la position initiale
        time.sleep(0.1)
        
        # Revenir à la position initiale
        pyautogui.moveTo(start_x, start_y, duration=0.25)
        
        # Pause avant de répéter le mouvement
        time.sleep(0.1)

# Appeler la fonction pour démarrer le mouvement
move_mouse()
