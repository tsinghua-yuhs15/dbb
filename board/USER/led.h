/**
  ******************************************************************************
  * �ӿڶ���
	* @Copyright Han ZHANG(haldak) @ Wenhao DING(Gilgamesh) | All rights reserved.
	* �廪��ѧ����ϵӲ���� & δ��ͨ����Ȥ�Ŷ�
	* Ӳ����ƴ���
  ******************************************************************************
  */
	
#ifndef __LED_H
#define	__LED_H

/* Includes ------------------------------------------------------------------*/
#include "stm32f10x.h"

/* Exported types ------------------------------------------------------------*/
/* Exported constants --------------------------------------------------------*/
/* Exported macro ------------------------------------------------------------*/
//  the macro definition to trigger the led on or off 
//  1 - off
//  0 - on
//
#define ON  1
#define OFF 0

// a macro to operate the LED
#define LED(a)	if (a)	\
					GPIO_SetBits(GPIOD,GPIO_Pin_2);\
					else		\
					GPIO_ResetBits(GPIOD,GPIO_Pin_2)

/* Exported functions ------------------------------------------------------- */
void LED_GPIO_Config(void);

#endif /* __LED_H */
