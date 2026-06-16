# Use slim Python 3.10 — compatible with torch, transformers, sentence-transformers
FROM python:3.10-slim

# Avoid interactive prompts during apt installs
ENV DEBIAN_FRONTEND=noninteractive

# Set working directory
WORKDIR /code

# Install CPU-only torch first (avoids pulling ~2.5 GB CUDA build)
RUN pip install --no-cache-dir torch --index-url https://download.pytorch.org/whl/cpu

# Copy and install remaining requirements
COPY ./requirements.txt /code/requirements.txt
RUN pip install --no-cache-dir --upgrade -r /code/requirements.txt

# Pre-download the HuggingFace model at build time (not runtime)
# so the container works offline and starts instantly
RUN python -c "from transformers import AutoTokenizer, AutoModel; \
    AutoTokenizer.from_pretrained('sentence-transformers/all-MiniLM-L6-v2'); \
    AutoModel.from_pretrained('sentence-transformers/all-MiniLM-L6-v2')"

# Copy all AI service files (respects .dockerignore — excludes .venv, __pycache__, etc.)
COPY . .

# Allow HuggingFace cache writes (needed if model re-caches at runtime)
RUN chmod -R 777 /code

# HuggingFace Spaces requires port 7860
EXPOSE 7860
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "7860"]