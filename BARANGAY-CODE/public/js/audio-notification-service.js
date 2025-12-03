/**
 * Audio Notification Service
 * Manages audio alerts for upcoming reservations
 */

class AudioNotificationService {
    constructor(audioPath = '/audio/bedside-clock-alarm-95792.mp3') {
        this.audioPath = audioPath;
        this.audioElement = null;
        this.isPlaying = false;
        this.storageKey = 'audioPlayedReservations';
        this.audioIntervals = {}; // Store intervals for each reservation
        this.currentReservationId = null; // Track which reservation is currently playing continuous audio
        this.endedEventListener = null; // Store the ended event listener
        this.init();
    }
    
    // Get played reservations from localStorage
    getPlayedReservations() {
        try {
            const stored = localStorage.getItem(this.storageKey);
            return stored ? JSON.parse(stored) : [];
        } catch (e) {
            console.warn('Could not read from localStorage:', e);
            return [];
        }
    }
    
    // Save played reservation to localStorage
    savePlayedReservation(reservationId) {
        try {
            const played = this.getPlayedReservations();
            if (!played.includes(reservationId)) {
                played.push(reservationId);
                localStorage.setItem(this.storageKey, JSON.stringify(played));
            }
        } catch (e) {
            console.warn('Could not write to localStorage:', e);
        }
    }
    
    // Check if reservation was already played
    hasBeenPlayed(reservationId) {
        return this.getPlayedReservations().includes(reservationId);
    }
    
    // Clear all played reservations (for testing)
    clearPlayedReservations() {
        try {
            localStorage.removeItem(this.storageKey);
            console.log('[AudioService] Cleared all played reservations from storage');
        } catch (e) {
            console.warn('Could not clear localStorage:', e);
        }
    }
    
    // Start continuous looping audio for a reservation
    startContinuousAudio(reservationId, volume = 0.7) {
        // If audio is already playing for this or another reservation, don't restart
        if (this.isPlaying && this.currentReservationId === reservationId) {
            console.log(`[AudioService] Audio already playing for reservation ${reservationId}`);
            return;
        }
        
        // Stop any existing audio
        this.stop();
        
        // Clear any existing intervals
        if (this.audioIntervals[reservationId]) {
            clearInterval(this.audioIntervals[reservationId]);
            delete this.audioIntervals[reservationId];
        }
        
        console.log(`[AudioService] Starting continuous looping audio for reservation ${reservationId}`);
        
        this.currentReservationId = reservationId;
        
        try {
            // Set up audio element for looping
            this.audioElement.volume = Math.max(0, Math.min(1, volume));
            this.audioElement.loop = true; // Enable continuous looping
            
            // Play the audio
            const playPromise = this.audioElement.play();
            
            if (playPromise !== undefined) {
                playPromise
                    .then(() => {
                        console.log(`[AudioService] ✅ Continuous audio playing for reservation ${reservationId}`);
                        this.isPlaying = true;
                    })
                    .catch(error => {
                        console.error(`[AudioService] ❌ Audio playback failed:`, error.message);
                        this.isPlaying = false;
                        this.currentReservationId = null;
                    });
            }
            
            // Remove old ended listener if it exists
            if (this.endedEventListener) {
                this.audioElement.removeEventListener('ended', this.endedEventListener);
            }
            
            // Handle when audio ends (shouldn't happen with loop, but just in case)
            this.endedEventListener = () => {
                if (this.currentReservationId === reservationId && this.audioElement.loop) {
                    // If loop is enabled but audio ended, restart it
                    this.audioElement.play().catch(err => {
                        console.error('[AudioService] Error restarting looped audio:', err);
                    });
                }
            };
            this.audioElement.addEventListener('ended', this.endedEventListener);
            
        } catch (error) {
            console.error(`[AudioService] ❌ Error starting continuous audio:`, error);
            this.isPlaying = false;
            this.currentReservationId = null;
        }
    }
    
    // Start repeating audio every 1 minute (legacy method, kept for compatibility)
    startRepeatingAudio(reservationId, interval = 60000) {
        // Clear any existing interval for this reservation
        if (this.audioIntervals[reservationId]) {
            clearInterval(this.audioIntervals[reservationId]);
        }
        
        console.log(`[AudioService] Starting repeating audio for reservation ${reservationId} (every ${interval}ms)`);
        
        // Play immediately
        this.playOnce(reservationId, 0.7);
        
        // Then repeat every interval
        this.audioIntervals[reservationId] = setInterval(() => {
            console.log(`[AudioService] Repeating audio for reservation ${reservationId}`);
            this.playOnce(reservationId, 0.7);
        }, interval);
    }
    
    // Stop continuous or repeating audio for a reservation
    stopRepeatingAudio(reservationId) {
        if (this.audioIntervals[reservationId]) {
            clearInterval(this.audioIntervals[reservationId]);
            delete this.audioIntervals[reservationId];
            console.log(`[AudioService] Stopped repeating audio for reservation ${reservationId}`);
        }
        
        // Stop continuous audio if it's for this reservation
        if (this.currentReservationId === reservationId) {
            this.stop();
            this.currentReservationId = null;
            console.log(`[AudioService] Stopped continuous audio for reservation ${reservationId}`);
        }
    }
    
    // Stop all audio (used when dismissing)
    stopAllAudio() {
        // Clear all intervals
        Object.keys(this.audioIntervals).forEach(reservationId => {
            clearInterval(this.audioIntervals[reservationId]);
            delete this.audioIntervals[reservationId];
        });
        
        this.stop();
        this.currentReservationId = null;
        console.log('[AudioService] Stopped all audio');
    }
    
    // Play audio once (without checking if already played)
    playOnce(reservationId, volume = 0.7) {
        if (this.isPlaying) {
            this.stop();
        }

        try {
            this.audioElement.volume = Math.max(0, Math.min(1, volume));
            
            const playPromise = this.audioElement.play();
            
            if (playPromise !== undefined) {
                playPromise
                    .then(() => {
                        console.log(`[AudioService] ✅ Audio playing for reservation ${reservationId}`);
                        this.isPlaying = true;
                    })
                    .catch(error => {
                        console.error(`[AudioService] ❌ Audio playback failed:`, error.message);
                        this.isPlaying = false;
                    });
            }
            
            return true;
        } catch (error) {
            console.error(`[AudioService] ❌ Error playing audio:`, error);
            return false;
        }
    }

    init() {
        if (!this.audioElement) {
            this.audioElement = new Audio();
            this.audioElement.src = this.audioPath;
            this.audioElement.preload = 'auto';
            
            this.audioElement.addEventListener('play', () => {
                this.isPlaying = true;
            });
            
            this.audioElement.addEventListener('ended', () => {
                this.isPlaying = false;
            });
            
            this.audioElement.addEventListener('error', (e) => {
                console.error('Audio playback error:', e);
                this.isPlaying = false;
            });
        }
    }

    play(reservationId, volume = 0.7) {
        console.log(`[AudioService] Attempting to play audio for reservation ${reservationId}`);
        console.log(`[AudioService] Audio file: ${this.audioPath}`);
        console.log(`[AudioService] Already played: ${this.hasBeenPlayed(reservationId)}`);
        
        if (this.hasBeenPlayed(reservationId)) {
            console.log(`[AudioService] Audio already played for this reservation, skipping`);
            return false;
        }

        if (this.isPlaying) {
            console.log(`[AudioService] Audio already playing, stopping first`);
            this.stop();
        }

        try {
            this.audioElement.volume = Math.max(0, Math.min(1, volume));
            this.savePlayedReservation(reservationId);
            
            console.log(`[AudioService] Volume set to ${this.audioElement.volume}`);
            console.log(`[AudioService] Calling play() method`);
            
            const playPromise = this.audioElement.play();
            
            if (playPromise !== undefined) {
                playPromise
                    .then(() => {
                        console.log(`[AudioService] ✅ Audio notification playing for reservation ${reservationId}`);
                        this.isPlaying = true;
                    })
                    .catch(error => {
                        console.error(`[AudioService] ❌ Audio playback failed:`, error.message);
                        this.isPlaying = false;
                    });
            }
            
            return true;
        } catch (error) {
            console.error(`[AudioService] ❌ Error playing audio:`, error);
            return false;
        }
    }

    stop() {
        try {
            if (this.audioElement) {
                // Remove ended event listener
                if (this.endedEventListener) {
                    this.audioElement.removeEventListener('ended', this.endedEventListener);
                    this.endedEventListener = null;
                }
                
                this.audioElement.pause();
                this.audioElement.currentTime = 0;
                this.audioElement.loop = false; // Disable looping when stopping
                this.isPlaying = false;
            }
        } catch (error) {
            console.error('Error stopping audio:', error);
        }
    }

    setVolume(level) {
        if (this.audioElement) {
            this.audioElement.volume = Math.max(0, Math.min(1, level));
        }
    }

    getVolume() {
        return this.audioElement ? this.audioElement.volume : 0;
    }

    getIsPlaying() {
        return this.isPlaying;
    }

}

const audioNotificationService = new AudioNotificationService();
