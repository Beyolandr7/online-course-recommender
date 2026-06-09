import pickle
import numpy as np
import torch

from transformers import AutoTokenizer, AutoModel
from sklearn.metrics.pairwise import cosine_similarity

# Load model hasil training
with open("model_experiment_dropped.pkl", "rb") as f:
    model_data = pickle.load(f)

df = model_data["dataframe"]
bert_norm_dropped = model_data["bert_norm_dropped"]
metadata_features_dropped = model_data["metadata_features_dropped"]
encoder = model_data["encoder"]

MODEL_NAME = "sentence-transformers/all-MiniLM-L6-v2"

tokenizer = AutoTokenizer.from_pretrained(MODEL_NAME)
model = AutoModel.from_pretrained(MODEL_NAME)


def mean_pooling(model_output, attention_mask):
    token_embeddings = model_output[0]

    input_mask_expanded = (
        attention_mask.unsqueeze(-1)
        .expand(token_embeddings.size())
        .float()
    )

    return torch.sum(
        token_embeddings * input_mask_expanded,
        1
    ) / torch.clamp(
        input_mask_expanded.sum(1),
        min=1e-9
    )


def recommend_courses(user_input, top_n=5):

    encoded = tokenizer(
        [user_input],
        padding=True,
        truncation=True,
        max_length=512,
        return_tensors="pt"
    )

    with torch.no_grad():
        output = model(**encoded)

    user_emb = mean_pooling(
        output,
        encoded["attention_mask"]
    )

    user_emb = user_emb.numpy()

    user_norm = (
        user_emb /
        np.linalg.norm(user_emb, axis=1, keepdims=True)
    )

    similarity = cosine_similarity(
        user_norm,
        bert_norm_dropped
    ).flatten()

    top_indices = np.argsort(similarity)[::-1][:top_n]

    results = []

    for idx in top_indices:

        course = df.iloc[idx]

        results.append({
            "course_id": str(idx),
            "title": course["course_title"],
            "score": float(similarity[idx])
        })

    return results