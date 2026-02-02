<?php

namespace Features\Auth\Shared\Domain;

/**
 * UserDetails Domain Entity
 *
 * Represents detailed user information stored in auth.user_details table
 * This is a separate entity from User to follow Single Responsibility Principle
 */
class UserDetails
{
    private ?int $id;
    private string $userId;

    // Personal Information
    private ?string $firstName;
    private ?string $lastName;
    private ?string $phone;
    private ?\DateTime $dateOfBirth;
    private ?string $gender;

    // Address Information
    private ?string $unitNo;
    private ?string $streetName;
    private ?string $city;
    private ?string $state;
    private ?string $postcode;
    private ?string $country;

    // Professional Information
    private ?string $employeeId;
    private ?int $telegramId;

    // Profile Information
    private ?string $bio;
    private ?string $profilePicture;
    private ?array $preferences;

    // Timestamps
    private ?\DateTime $createdAt;
    private ?\DateTime $updatedAt;

    public function __construct(
        string $userId,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $phone = null,
        ?\DateTime $dateOfBirth = null,
        ?string $gender = null,
        ?string $unitNo = null,
        ?string $streetName = null,
        ?string $city = null,
        ?string $state = null,
        ?string $postcode = null,
        ?string $country = null,
        ?string $employeeId = null,
        ?int $telegramId = null,
        ?string $bio = null,
        ?string $profilePicture = null,
        ?array $preferences = null,
        ?int $id = null,
        ?\DateTime $createdAt = null,
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->unitNo = $unitNo;
        $this->streetName = $streetName;
        $this->city = $city;
        $this->state = $state;
        $this->postcode = $postcode;
        $this->country = $country;
        $this->employeeId = $employeeId;
        $this->telegramId = $telegramId;
        $this->bio = $bio;
        $this->profilePicture = $profilePicture;
        $this->preferences = $preferences ?? [];
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // === Getters ===

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    // Personal Information Getters
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getFullName(): ?string
    {
        if (!$this->firstName && !$this->lastName) {
            return null;
        }
        return trim("{$this->firstName} {$this->lastName}");
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getDateOfBirth(): ?\DateTime
    {
        return $this->dateOfBirth;
    }

    public function getAge(): ?int
    {
        if (!$this->dateOfBirth) {
            return null;
        }
        return (int) $this->dateOfBirth->diff(new \DateTime())->y;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    // Address Information Getters
    public function getUnitNo(): ?string
    {
        return $this->unitNo;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getFullAddress(): ?string
    {
        $addressParts = array_filter([
            $this->unitNo,
            $this->streetName,
            $this->city,
            $this->state,
            $this->postcode,
            $this->country
        ]);

        return !empty($addressParts) ? implode(', ', $addressParts) : null;
    }

    // Professional Information Getters
    public function getEmployeeId(): ?string
    {
        return $this->employeeId;
    }

    public function getTelegramId(): ?int
    {
        return $this->telegramId;
    }

    // Profile Information Getters
    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function getPreferences(): array
    {
        return $this->preferences ?? [];
    }

    public function getPreference(string $key, $default = null)
    {
        return $this->preferences[$key] ?? $default;
    }

    // Timestamp Getters
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    // === Setters (Fluent Interface) ===

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function setDateOfBirth(?\DateTime $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function setAddress(
        ?string $unitNo,
        ?string $streetName,
        ?string $city,
        ?string $state,
        ?string $postcode,
        ?string $country
    ): self {
        $this->unitNo = $unitNo;
        $this->streetName = $streetName;
        $this->city = $city;
        $this->state = $state;
        $this->postcode = $postcode;
        $this->country = $country;
        return $this;
    }

    public function setEmployeeId(?string $employeeId): self
    {
        $this->employeeId = $employeeId;
        return $this;
    }

    public function setTelegramId(?int $telegramId): self
    {
        $this->telegramId = $telegramId;
        return $this;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;
        return $this;
    }

    public function setProfilePicture(?string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    public function setPreferences(array $preferences): self
    {
        $this->preferences = $preferences;
        return $this;
    }

    public function setPreference(string $key, $value): self
    {
        $this->preferences[$key] = $value;
        return $this;
    }

    // === Factory Methods ===

    /**
     * Create a new UserDetails instance with minimal information
     */
    public static function create(
        string $userId,
        ?string $firstName = null,
        ?string $lastName = null
    ): self {
        return new self(
            userId: $userId,
            firstName: $firstName,
            lastName: $lastName
        );
    }

    /**
     * Create UserDetails from database row
     */
    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            phone: $data['phone'] ?? null,
            dateOfBirth: isset($data['date_of_birth'])
                ? new \DateTime($data['date_of_birth'])
                : null,
            gender: $data['gender'] ?? null,
            unitNo: $data['unit_no'] ?? null,
            streetName: $data['street_name'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            postcode: $data['postcode'] ?? null,
            country: $data['country'] ?? null,
            employeeId: $data['employee_id'] ?? null,
            telegramId: $data['telegram_id'] ?? null,
            bio: $data['bio'] ?? null,
            profilePicture: $data['profile_picture'] ?? null,
            preferences: isset($data['preferences'])
                ? (is_string($data['preferences'])
                    ? json_decode($data['preferences'], true)
                    : $data['preferences'])
                : null,
            id: $data['id'] ?? null,
            createdAt: isset($data['created_at'])
                ? new \DateTime($data['created_at'])
                : null,
            updatedAt: isset($data['updated_at'])
                ? new \DateTime($data['updated_at'])
                : null
        );
    }

    // === Conversion Methods ===

    /**
     * Convert to array for database storage
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'date_of_birth' => $this->dateOfBirth?->format('Y-m-d'),
            'gender' => $this->gender,
            'unit_no' => $this->unitNo,
            'street_name' => $this->streetName,
            'city' => $this->city,
            'state' => $this->state,
            'postcode' => $this->postcode,
            'country' => $this->country,
            'employee_id' => $this->employeeId,
            'telegram_id' => $this->telegramId,
            'bio' => $this->bio,
            'profile_picture' => $this->profilePicture,
            'preferences' => $this->preferences ? json_encode($this->preferences) : null,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Convert to array for JSON serialization (API responses)
     */
    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'full_name' => $this->getFullName(),
            'phone' => $this->phone,
            'date_of_birth' => $this->dateOfBirth?->format('Y-m-d'),
            'age' => $this->getAge(),
            'gender' => $this->gender,
            'address' => [
                'unit_no' => $this->unitNo,
                'street_name' => $this->streetName,
                'city' => $this->city,
                'state' => $this->state,
                'postcode' => $this->postcode,
                'country' => $this->country,
                'full_address' => $this->getFullAddress(),
            ],
            'employee_id' => $this->employeeId,
            'telegram_id' => $this->telegramId,
            'bio' => $this->bio,
            'profile_picture' => $this->profilePicture,
            'preferences' => $this->preferences,
            'created_at' => $this->createdAt?->format('c'),
            'updated_at' => $this->updatedAt?->format('c'),
        ];
    }

    // === Validation Methods ===

    /**
     * Check if the profile is complete
     */
    public function isProfileComplete(): bool
    {
        return !empty($this->firstName)
            && !empty($this->lastName)
            && !empty($this->phone);
    }

    /**
     * Check if address is complete
     */
    public function hasCompleteAddress(): bool
    {
        return !empty($this->streetName)
            && !empty($this->city)
            && !empty($this->postcode)
            && !empty($this->country);
    }
}
