from fastapi import FastAPI
from pydantic import BaseModel
from typing import List, Optional

app = FastAPI(title="Online Course Recommender AI Service")

class RecommendationRequest(BaseModel):
    user_id: str
    interests: Optional[List[str]] = None

class CourseRecommendation(BaseModel):
    course_id: str
    title: str
    score: float

class RecommendationResponse(BaseModel):
    user_id: str
    recommendations: List[CourseRecommendation]

@app.get("/")
def read_root():
    return {"message": "Welcome to AI Recommendation Service"}

@app.post("/recommend", response_model=RecommendationResponse)
def get_recommendation(request: RecommendationRequest):
    # Placeholder logic
    # In production, this would use ML models to generate recommendations
    return {
        "user_id": request.user_id,
        "recommendations": [
            {"course_id": "cs101", "title": "Introduction to Computer Science", "score": 0.9},
            {"course_id": "math202", "title": "Calculus II", "score": 0.85}
        ]
    }

