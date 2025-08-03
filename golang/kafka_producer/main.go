package main

import (
	"bufio"
	"context"
	"encoding/json"
	"fmt"
	"github.com/brianvoe/gofakeit/v6"
	"github.com/google/uuid"
	"github.com/segmentio/kafka-go"
	"log"
	"math/rand"
	"os"
	"strconv"
	"time"
)

type Message struct {
	ID      string `json:"id"`
	Name    string `json:"name"`
	Payment string `json:"payment"`
}

func main() {
	broker := os.Getenv("KAFKA_BROKER")
	hostname, _ := os.Hostname()
	fmt.Println("Отправка сообщений в kafka: ", broker)
	fmt.Println("HOSTNAME:", hostname)

	//&kafka.LeastBytes{}, // Сообщение уходит в партицию, которая сейчас меньше всего загружена (в ней меньше байт).
	//&kafka.Hash{}       //Сообщения с одинаковым ключом всегда идут в одну и ту же партицию.
	//&kafka.RoundRobin{} //Сообщения равномерно идут по очереди во все партиции.
	//&kafka.Cyclic       //Как round robin, но без учета ключей.
	writer := kafka.Writer{
		Addr:     kafka.TCP(broker),
		Topic:    "test_topic_2",
		Balancer: &kafka.LeastBytes{},
	}
	defer writer.Close()

	ctx := context.Background()
	key := uuid.New().String()

	scanner := bufio.NewScanner(os.Stdin)
	fmt.Println("Введи команду: send (отправить сообщение) или exit (выход из приложения)")

	for {
		fmt.Print("> ")
		if !scanner.Scan() {
			break
		}
		cmd := scanner.Text()

		switch cmd {
		case "send":
			payment := strconv.Itoa(rand.Intn(100))
			msg := Message{ID: key, Name: gofakeit.Name(), Payment: payment}
			err := sendJSONToKafka(ctx, &writer, key, msg)
			if err != nil {
				log.Fatalf("Ошибка отправки: %v", err)
			}
			log.Println("Сообщение отправлено успешно ", payment)
		case "exit":
			fmt.Println("Выход.")
			return
		default:
			fmt.Println("Неизвестная команда. Доступны: send, exit")
		}
	}

	if err := scanner.Err(); err != nil {
		log.Fatalln("Ошибка чтения из stdin:", err)
	}

}

func sendJSONToKafka(ctx context.Context, writer *kafka.Writer, key string, msg Message) error {
	data, err := json.Marshal(msg)
	if err != nil {
		log.Fatalf("ошибка сериализации: %v", err)
	}

	kafkaMsg := kafka.Message{
		Key:   []byte(key),
		Value: data,
		Time:  time.Now(),
	}

	return writer.WriteMessages(ctx, kafkaMsg)
}
