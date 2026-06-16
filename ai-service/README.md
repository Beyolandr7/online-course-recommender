---
title: Online Course Recommender AI Service
emoji: 🎓
colorFrom: indigo
colorTo: purple
sdk: docker
app_port: 7860
pinned: false
---

# Online Course Recommender — AI Service

FastAPI-based recommendation engine using **sentence-transformers/all-MiniLM-L6-v2** and cosine similarity to suggest relevant online courses based on user interests.

## Endpoints

| Method | Path | Description |
|--------|------|-------------|
| `GET` | `/` | Health check |
| `POST` | `/recommend` | Get course recommendations |
| `GET` | `/courses/{course_id}` | Get course detail by index |

## POST `/recommend`

**Request body:**
```json
{
  "user_id": "1",
  "interest": "Data science, Deep Learning"
}
```

**Response:**
```json
{
  "user_id": "1",
  "recommendations": [
    {
      "course_id": "1240",
      "title": "The Data Science Profession",
      "score": 0.675,
      "description": "...",
      "level": "Beginner",
      "url": "https://...",
      "platform": "Coursera",
      "skills": "Python, Statistics"
    }
  ]
}
```
