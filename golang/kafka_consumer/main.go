package main

import (
	"bufio"
	"context"
	"fmt"
	"github.com/segmentio/kafka-go"
	"log"
	"os"
)

type Message struct {
	ID      string `json:"id"`
	Name    string `json:"name"`
	Payment string `json:"payment"`
}

func main() {
	broker := os.Getenv("KAFKA_BROKER")
	hostname, _ := os.Hostname()
	fmt.Println("Чтение сообщений из kafka: ", broker)
	fmt.Println("HOSTNAME:", hostname)

	scanner := bufio.NewScanner(os.Stdin)
	fmt.Println("Введи команду: read (прочитать сообщения) или exit (выход из приложения)")

	for {
		fmt.Print("> ")
		if !scanner.Scan() {
			break
		}
		cmd := scanner.Text()

		switch cmd {
		case "read":
			fmt.Print("Введите название топика: ")
			scanner.Scan()
			topic := scanner.Text()
			if topic == "exit" {
				fmt.Println("Выход.")
				return
			}

			reader := kafka.NewReader(kafka.ReaderConfig{
				Brokers:  []string{broker},
				Topic:    topic,
				MaxBytes: 10e6, // 10MB
			})

			for {
				m, err := reader.ReadMessage(context.Background())
				if err != nil {
					break
				}
				fmt.Printf("Сообщение offset %d: %s = %s\n", m.Offset, string(m.Key), string(m.Value))
			}

			if err := reader.Close(); err != nil {
				log.Fatal("Ошибка close reader:", err)
			}
		case "exit":
			fmt.Println("Выход.")
			return
		default:
			fmt.Println("Неизвестная команда. Доступны: read, exit")
		}
	}

	if err := scanner.Err(); err != nil {
		log.Fatalln("Ошибка чтения из stdin:", err)
	}
}
